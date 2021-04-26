<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Lote;

class ReservasController extends Controller
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
                ->json(Reserva::paginate($request->input('perPage') ?? 10));
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
                return response()->json(Reserva::with('cliente')->with('lote')->get(), 200);
            } catch(\Exeception $e) {
                return response()->json([], 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Pega Item reversa
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getReserva(Request $request, int $loteId)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                return response()->json(Reserva::with('cliente')->with('lote')->where('loteId', $loteId)->first(), 200);
            } catch(\Exeception $e) {
                return response()->json(null, 404);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Cria Reserva
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $validated = $request->validate([
                    'clienteId' => 'required|int',
                    'loteId' => 'required|int|unique:reservas',
                ]);

                $reserva = Reserva::create(array_merge($request->all(), [
                    'createdBy' => $request->user()->id,
                    'status' => 1
                ]));
                $reserva->save();
                
                return response()->json([
                    'success' => true, 
                    'reserva' => Reserva::with('cliente')
                                ->with('lote')
                                ->where('id', $reserva->id)
                                ->first()
                ], 200);
            } catch (\Exeception $e) {
                return response()->json(['success' => false], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }


    /**
     * Marca como Fechado/Vendido
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setAsSold(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $validated = $request->validate([
                    'loteId' => 'required|int',
                ]);

                $reserva = Reserva::where('loteId', $request->input('loteId'))->first();
                $reserva->status = 2;
                $reserva->updatedBy = $request->user()->id;
                $reserva->save();

                return response()->json([
                    'success' => true,
                    'reserva' => $reserva
                ], 200);
            } catch (\Exeception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }


    /**
     * Remove/Exclui Reserva
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        if (Gate::allows('admin', $request) || Gate::allows('manager', $request)){
            try {
                $validated = $request->validate([
                    'loteId' => 'required|int',
                ]);

                $reserva = Reserva::where('loteId', $request->input('loteId'))->first();
                
                if($reserva && $reserva->status === 1) { 
                    $success = $reserva->delete();
                    if ($success){
                        return response()->json(['success' => true], 200);
                    }
                    return response()->json(['success' => false], 200);
                }
                return response()->json(['success' => false, 'error' => 'Reserva não pode ser removida!'], 200);
            } catch (\Exeception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
            }
        }
        return response()->json(['error' => 'Sem permissão!'], 403);
    }

    /**
     * Estatisticas de Reservas
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function estatisticas(Request $request)
    {
        $total = Lote::count();
        $reservas = Reserva::where('status', 1)->count();
        $fechadas = Reserva::where('status', 2)->count();

        $disponiveis = $total - ($reservas + $fechadas);

        return response()->json([
            'unidades' => $total,
            'disponiveis' => $disponiveis,
            'reservadas' => $reservas,
            'fechadas' => $fechadas,
        ]);
    }
}
