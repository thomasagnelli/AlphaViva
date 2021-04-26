<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Unidade;
use App\Models\Quadra;

class UnidadesController extends Controller
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
     * Página das Unidades
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            return view('unidades');
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Paginação de Resultados
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paginate(Request $request)
    {   
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            return response()->json(Unidade::with('quadras')->paginate($request->input('perPage') ?? 10));
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }


    /**
     * Cria Unidade
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $unidade = Unidade::create([
                    'name' => $request->input('name'),
                    'description' =>$request->input('description'),
                    'location' =>$request->input('location'),
                    'active' =>$request->input('active'),
                    'createdBy' => $request->user()->id
                ]);
                return response()->json($unidade);
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Atualiza Unidade
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {   
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $unidade = Unidade::find($request->input('id'));
                $unidade->name = $request->input('name');
                $unidade->location = $request->input('location');
                $unidade->description = $request->input('description');
                $unidade->active = $request->input('active');
                $unidade->updatedBy = $request->user()->id;
                $unidade->save();
                return response()->json($unidade);
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Exclui/Remove Unidade
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            $countQuadras = Quadra::where('unidadeId', $request->input('id'))->count();
            if ($countQuadras === 0) {
                try {
                    $unidade = Unidade::find($request->input('id'));
                    $success = $unidade->delete();
                    return response()->json(['success' => $success], 200);
                } catch (\Exeception $e) {
                    return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
                }
            }
            return response()->json(['success' => false, 'error' => 'Unidade não vazia, não pode ser excluida!'], 400);
        }
        return response()->json(['success' => false, 'error' => 'Sem permissão!'], 403);
    }
}
