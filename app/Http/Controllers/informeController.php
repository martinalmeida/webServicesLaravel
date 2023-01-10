<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Placa;
use App\Models\Alquiler;
use DataTables;

class informeController extends Controller
{
    public function index()
    {
        if (auth()->user()->rolId == 1 or auth()->user()->rolId == 2) {
            return view('informes.informes', ['routeActive' => 'informes']);
        } else {
            return view('home.home', ['routeActive' => 'home']);
        }
    }

    public function alquiler()
    {
        if (auth()->user()->rolId == 1 or auth()->user()->rolId == 2) {
            return view('informes.alquiler', ['routeActive' => 'informes']);
        } else {
            return view('home.home', ['routeActive' => 'home']);
        }
    }

    public function flete()
    {
        if (auth()->user()->rolId == 1 or auth()->user()->rolId == 2) {
            return view('informes.fletes', ['routeActive' => 'informes']);
        } else {
            return view('home.home', ['routeActive' => 'home']);
        }
    }

    public function movimiento()
    {
        if (auth()->user()->rolId == 1 or auth()->user()->rolId == 2) {
            return view('informes.movimientos', ['routeActive' => 'informes']);
        } else {
            return view('home.home', ['routeActive' => 'home']);
        }
    }

    public function selectPlacaInforme(Request $request)
    {
        if ($request->ajax()) {
            $placas = Placa::join('usersplacas', 'usersplacas.placaId', '=', 'maquinarias.id')
                ->join('users', 'usersplacas.userId', '=', 'users.id')
                ->where('usersplacas.status', '=', 1)
                ->where('usersplacas.userId', '=', auth()->user()->id)
                ->where('users.nit', '=', auth()->user()->nit)
                ->orderBy('maquinarias.placa', 'desc')
                ->get(['maquinarias.id', 'maquinarias.placa']);
        }
        return response()->json($placas);
    }

    public function dataTableInfomeAlquiler(Request $request)
    {
        if ($request->ajax()) {
            $idPlaca = $request->placa;
            $inicio = $request->fechaInicio;
            $final = $request->fechaFin;

            $data = Alquiler::join('maquinarias', 'registros_alquiler.idMaquinaria', '=', 'maquinarias.id')
                ->join('alquiler', 'registros_alquiler.idAlquiler', '=', 'alquiler.id')
                ->join('contratos', 'alquiler.idContrato', '=', 'contratos.id')
                ->leftjoin('deducibles_alquiler', 'deducibles_alquiler.idRegistro', '=', 'registros_alquiler.id')
                ->select(
                    'maquinarias.placa',
                    'registros_alquiler.fechaInicio',
                    'registros_alquiler.fechaFin',
                    'contratos.titulo',
                    'registros_alquiler.horometroInicial',
                    'registros_alquiler.horometroFin',
                    'alquiler.standby',
                    'alquiler.horaTarifa',
                    'deducibles_alquiler.admon',
                    'deducibles_alquiler.retefuente',
                    'deducibles_alquiler.reteica',
                    'deducibles_alquiler.anticipo',
                    'deducibles_alquiler.otros',
                    'deducibles_alquiler.observacion'
                )
                ->selectRaw('(registros_alquiler.horometroFin - registros_alquiler.horometroInicial) AS totalHoras')
                ->selectRaw('((registros_alquiler.horometroFin - registros_alquiler.horometroInicial) * alquiler.horaTarifa) AS subTotal')
                ->selectRaw('(((registros_alquiler.horometroFin - registros_alquiler.horometroInicial) * (alquiler.horaTarifa)) - (IFNULL(deducibles_alquiler.admon,0) + IFNULL(deducibles_alquiler.retefuente,0) + IFNULL(deducibles_alquiler.reteica,0) + IFNULL(deducibles_alquiler.anticipo,0) + IFNULL(deducibles_alquiler.otros,0))) AS total',)
                ->where('registros_alquiler.status', '=', 1)
                ->where('alquiler.status', '=', 1)
                ->where('registros_alquiler.idMaquinaria', '=', $idPlaca)
                ->whereRaw("STR_TO_DATE(registros_alquiler.fechaInicio, '%m/%d/%Y') >= STR_TO_DATE('$inicio', '%m/%d/%Y')")
                ->whereRaw("STR_TO_DATE(registros_alquiler.fechaFin, '%m/%d/%Y') <= STR_TO_DATE('$final', '%m/%d/%Y')")
                ->get();

            return Datatables::of($data)->addIndexColumn()->make(true);
        }
        return view('informeAlquiler');
    }
}
