<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
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
     * Página de Usuários
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Gate::allows('admin', $request)){
            return view('usuarios');
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
        if (Gate::allows('admin', $request)){
            try {
                return response()
                ->json(User::with('role')->paginate($request->input('perPage') ?? 10));
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
        if (Gate::allows('admin', $request)){
            try {
                return response()->json(User::all(), 200);
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Pega Usuário de acordo com $id
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function getUser(Request $request, int $id)
    {
        if (Gate::allows('admin', $request) || Gate::allows('is_self', $request)){
            try {
                $user = User::find($id);
                if ($user) {
                    return response()->json($user, 200);
                }
                return response()->json(null, 404);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Cria Usiário
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function create(Request $request)
    {
        if (Gate::allows('admin', $request)){
            try {

                $validated = $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|string|email|unique:users',
                    'roleId' => 'required|int',
                    'active' => 'int',
                ]);
                $user = (new User())
                ->fill($validated)
                ->passwordHash()
                ->save();
                return response()->json($user, 201);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Atualiza Usuário
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function update(Request $request)
    {
        if (Gate::allows('admin', $request) | Gate::allows('is_self', $request)){
            try {
                $validated = $request->validate([
                    'id' => 'required|int',
                    'name' => 'required|string',
                    'email' => 'required|string|email|'. Rule::unique('users')->ignore($request->input('id')),
                    'roleId' => 'int',
                    'active' => 'bool',
                ]);

                $user = User::find($request->input('id'));
                if ($user) {

                    if(Gate::allows('is_self', $request)) {
                        $request['roleId'] = $request->user()->roleId; 
                    }

                    $user->fill($request->all())
                    ->passwordHash();
                    $user->save();
                    return response()->json($user, 200);
                }
                return response()->json($user, 404);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Exclui/remove Usuário
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public static function delete(Request $request)
    {
        if (!Gate::allows('is_self', $request) && Gate::allows('admin', $request)){
            $validated = $request->validate([
                'id' => 'required|int',
            ]);

            $user = User::find($validated['id']);
            if($user === null) {
                return response()->json(['success' => false, 'error' => 'Usuário não encontrado!'], 404);
            }

            try {
                $success = $user->delete();
                return response()->json(['success' => $success], 200);
            } catch (\Exeception $e) {
                return response()->json(['success' => false], 400);
            }
        }
        return response()->json(['success' => false, 'error' => 'Sem permissão!'], 403);
    }
}
