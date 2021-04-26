<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Quadra;
use App\Models\Lote;
use App\Models\Reserva;

class LotesController extends Controller
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
                ->json(Lote::where('quadraId', $request->input('quadraId'))
                ->paginate($request->input('perPage') ?? 10));
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }


    /**
     * Resultados sem Paginação
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function noPaginate(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                return response()->json(Lote::with('quadra')->where('quadraId', $request->input('quadraId'))->get(), 200);
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Cria Lote
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $lote =  Lote::create([
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                    'quadraId' => $request->input('quadraId'),
                    'unidadeId' => $request->input('unidadeId'),
                    'createdBy' => $request->user()->id
                ]);
                return response()->json($lote);
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Atualiza Lote
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $lote = Lote::find($request->input('id'));
                $lote->name = $request->input('name');
                $lote->description = $request->input('description');
                $lote->updatedBy = $request->user()->id;
                $lote->save();
                return response()->json($lote);
            } catch (\Exeception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);    
    }

    /**
     * Exclui/Remove Lote
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            $lote = Lote::find($request->input('id'));
            if($lote === null) {
                return response()->json(['success' => false, 'error' => 'Lote não encontrado!'], 404);
            }

            $reservas = Reserva::where('loteId', $lote->id)->count();  
            if ($reservas === 0) {
                try {
                    $success = $lote->delete();
                    return response()->json(['success' => $success], 200);
                } catch (\Exeception $e) {
                    return response()->json(['success' => false], 400);
                }
            }
            return response()->json(['success' => false, 'error' => 'Lote com reserva, não pode ser excluido!'], 400);
        }
        return response()->json(['success' => false, 'error' => 'Sem permissão!'], 403);
    }
}
