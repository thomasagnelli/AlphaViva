<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Unidade;
use App\Models\Quadra;
use App\Models\Lote;

class QuadrasController extends Controller
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
     * Paginação de resultados
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paginate(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                return response()
                ->json(Quadra::with('lotes')->where('unidadeId', $request->input('unidadeId'))
                ->paginate($request->input('perPage') ?? 10));
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Cria Quadra
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $quadra = Quadra::create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'active' => $request->input('active'),
                    'unidadeId' => $request->input('unidadeId'),
                    'createdBy' => $request->user()->id
                ]);
                
                if ($quadra->id && ($request->input('lotes') > 0)){
                    for($i = 1; $i <= $request->input('lotes'); ++$i) {
                        Lote::create([
                            'name' => "Lote $i",
                            'description' => '',
                            'quadraId' => $quadra->id,
                            'status' => 1
                        ]);
                    }
                }
                
                return response()->json($quadra);
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Atualiza Quadra
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {   
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $quadra = Quadra::find($request->input('id'));
                $quadra->name = $request->input('name');
                $quadra->description = $request->input('description');
                $quadra->active = $request->input('active');
                $quadra->unidadeId = $request->input('unidadeId');
                $quadra->updatedBy = $request->user()->id;
                $quadra->save();
                return response()->json($quadra);
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Exclui/Remove Quadra
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            $countLotes = Lote::where('quadraId', $request->input('id'))->count();
            if ($countLotes === 0) {
                try {
                    $quadra = Quadra::find($request->input('id'));
                    $success = $quadra->delete();
                    return response()->json(['success' => $success], 200);
                } catch (\Exeception $e) {
                    return response()->json(['success' => false], 400);
                }
            }
            return response()->json(['success' => false, 'error' => 'Quadra não vazia, não pode ser excluida!'], 400);
        }
        return response()->json(['success' => false, 'error' => 'Sem permissão!'], 403);
    }
}
