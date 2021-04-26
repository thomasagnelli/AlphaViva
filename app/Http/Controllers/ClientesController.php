<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;

class ClientesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Página de Clientes
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            return view('clientes');
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Paginação de resultados
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paginate(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                return response()
                ->json(Cliente::with('user')->with('role')->paginate($request->input('perPage') ?? 10));
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }


    /**
     * Resultados  sem Paginação
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function noPaginate(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                return response()->json(Cliente::with('user')->get(), 200);
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Cria Cliente
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $user = User::where('email', $request->input('email'))->first();
                if ($user) {
                    return response()->json(['errors' => ['email' => ['Já existe um usuário registrado com o email especificado!']]], 422);
                } else {

                    $validated = $request->validate([
                        'name' => 'required|string',
                        'email' => 'required|string|email|unique:users',
                        'cpf' => 'string|unique:clientes',
                        'cnpj' => 'string|unique:clientes',
                        'phone' => 'string|min:13|max:14'
                    ]);

                    $user = User::create(
                        array_merge(
                            $validated,
                            [
                                'password' => $request->input('password') ?? $request->input('cpf') ?? $request->input('cnpj'),
                                'roleId' => Role::getByName('client'),
                                'createdBy' => $request->user()->id
                            ]
                        )
                    );
                    $user->passwordHash()->save();
                }
                
                $cliente = Cliente::create(
                    array_merge(
                        $validated,
                        [
                            'userId' => $user->id,
                            'createdBy' => $request->user()->id
                        ]
                    )
                );
                $cliente->save();

                return response()->json(Cliente::with('user')->where('id', $cliente->id)->first());
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Atualiza Cliente
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    { 
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $cliente = Cliente::find($request->input('id'));
                if (!$cliente) {
                    return response()->json(['error' => 'Cliente não encontrado!'], 404);
                }

                $validated = $request->validate([
                    'id' => 'required|int',
                    'name' => 'string',
                    'email' => "required|". Rule::unique('users')->ignore($cliente->user->id),
                    'cpf' => "string|". Rule::unique('clientes')->ignore($cliente->id),
                    'cnpj' => "string|". Rule::unique('clientes')->ignore($cliente->id),
                    'phone' => 'string|min:13|max:14'
                ]);
                
                $cliente->cpf = $validated['cpf'] ?? null;
                $cliente->cnpj = $validated['cnpj'] ?? null;
                $cliente->phone = $validated['phone'] ?? null;
                $cliente->user->email = $validated['email'] ?? null;
                $cliente->user->name = $validated['name'] ? ucwords($validated['name']) : null;
                $cliente->updatedBy = $request->user()->id;
                $cliente->user->updatedBy = $request->user()->id;
                $cliente->user->save();
                $cliente->save();

                return response()->json(Cliente::with('user')->where('id', $cliente->id)->first());
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Exclui Cliente
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            $validated = $request->validate([
                'id' => 'required|int',
            ]);

            $cliente = Cliente::find($validated['id']);
            if($cliente === null) {
                return response()->json(['success' => false, 'error' => 'Cliente não encontrado!'], 404);
            }

            try {
                $success = $cliente->delete();
                if($success) {
                    $cliente->user->delete();
                }
                return response()->json(['success' => $success], 200);
            } catch (\Exeception $e) {
                return response()->json(['success' => false], 400);
            }
            
            return response()->json(['success' => false, 'error' => 'Cliente com reserva, não pode ser excluido!'], 400);
        }
        return response()->json(['success' => false, 'error' => 'Sem permissão!'], 403);
    }
}
