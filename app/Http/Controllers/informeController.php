<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Placa;
use App\Models\Alquiler;
use App\Models\Flete;
use App\Models\Movimiento;

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

    public function dataTableInfomeFlete(Request $request)
    {
        if ($request->ajax()) {
            $idPlaca = $request->placa;
            $inicio = $request->fechaInicio;
            $final = $request->fechaFin;

            $data = Flete::join('maquinarias', 'registros_fletes.idMaquinaria', '=', 'maquinarias.id')
                ->join('fletes', 'registros_fletes.idFlete', '=', 'fletes.id')
                ->join('rutas', 'fletes.idRuta', '=', 'rutas.id')
                ->join('rutas_contratos', 'rutas_contratos.idRuta', '=', 'rutas.id')
                ->join('contratos', 'rutas_contratos.idContrato', '=', 'contratos.id')
                ->leftjoin('deducibles_fletes', 'deducibles_fletes.idRegistro', '=', 'registros_fletes.id')
                ->select(
                    'registros_fletes.codFicha',
                    'maquinarias.placa',
                    'registros_fletes.fechaInicio',
                    'registros_fletes.fechaFin',
                    'contratos.titulo',
                    'fletes.flete',
                    'deducibles_fletes.admon',
                    'deducibles_fletes.retefuente',
                    'deducibles_fletes.reteica',
                    'deducibles_fletes.anticipo',
                    'deducibles_fletes.otros',
                    'deducibles_fletes.observacion'
                )
                ->selectRaw("CONCAT(rutas.origen, ' - ', rutas.destino) AS ruta")
                ->selectRaw('((fletes.flete) - (IFNULL(deducibles_fletes.admon,0) + IFNULL(deducibles_fletes.retefuente,0) + IFNULL(deducibles_fletes.reteica,0) + IFNULL(deducibles_fletes.anticipo,0) + IFNULL(deducibles_fletes.otros,0))) AS total')
                ->where('registros_fletes.status', '=', 1)
                ->where('fletes.status', '=', 1)
                ->where('rutas_contratos.status', '=', 1)
                ->where('registros_fletes.idMaquinaria', '=', $idPlaca)
                ->whereRaw("STR_TO_DATE(registros_fletes.fechaInicio, '%m/%d/%Y') >= STR_TO_DATE('$inicio', '%m/%d/%Y')")
                ->whereRaw("STR_TO_DATE(registros_fletes.fechaFin, '%m/%d/%Y') <= STR_TO_DATE('$final', '%m/%d/%Y')")
                ->get();

            return Datatables::of($data)->addIndexColumn()->make(true);
        }
        return view('informeFlete');
    }

    public function dataTableInfomeMovimiento(Request $request)
    {
        if ($request->ajax()) {
            $idPlaca = $request->placa;
            $inicio = $request->fechaInicio;
            $final = $request->fechaFin;

            $data = Movimiento::join('maquinarias', 'registros_movimientos.idMaquinaria', '=', 'maquinarias.id')
                ->join('movimientos', 'registros_movimientos.idMovimeinto', '=', 'movimientos.id')
                ->join('rutas', 'movimientos.idRuta', '=', 'rutas.id')
                ->join('rutas_contratos', 'rutas_contratos.idRuta', '=', 'rutas.id')
                ->join('contratos', 'rutas_contratos.idContrato', '=', 'contratos.id')
                ->leftjoin('deducibles_movimientos', 'deducibles_movimientos.idRegistro', '=', 'registros_movimientos.id')
                ->select(
                    'registros_movimientos.codFicha',
                    'maquinarias.placa',
                    'registros_movimientos.fechaInicio',
                    'registros_movimientos.fechaFin',
                    'contratos.titulo',
                    'movimientos.kilometraje',
                    'movimientos.tarifa',
                    'registros_movimientos.mts3',
                    'registros_movimientos.peaje',
                    'registros_movimientos.movimientos',
                    'deducibles_movimientos.admon',
                    'deducibles_movimientos.retefuente',
                    'deducibles_movimientos.reteica',
                    'deducibles_movimientos.anticipo',
                    'deducibles_movimientos.otros',
                    'deducibles_movimientos.observacion'
                )
                ->selectRaw("CONCAT(rutas.origen, ' - ', rutas.destino) AS ruta")
                ->selectRaw('((((movimientos.kilometraje * movimientos.tarifa * registros_movimientos.mts3) * registros_movimientos.movimientos )+ (registros_movimientos.peaje) ) - (IFNULL(deducibles_movimientos.admon,0) + IFNULL(deducibles_movimientos.retefuente,0) + IFNULL(deducibles_movimientos.reteica,0) + IFNULL(deducibles_movimientos.anticipo,0) + IFNULL(deducibles_movimientos.otros,0))) AS total')
                ->where('registros_movimientos.status', '=', 1)
                ->where('movimientos.status', '=', 1)
                ->where('rutas_contratos.status', '=', 1)
                ->where('registros_movimientos.idMaquinaria', '=', $idPlaca)
                ->whereRaw("STR_TO_DATE(registros_movimientos.fechaInicio, '%m/%d/%Y') >= STR_TO_DATE('$inicio', '%m/%d/%Y')")
                ->whereRaw("STR_TO_DATE(registros_movimientos.fechaFin, '%m/%d/%Y') <= STR_TO_DATE('$final', '%m/%d/%Y')")
                ->get();

            return Datatables::of($data)->addIndexColumn()->make(true);
        }
        return view('informeMovimiento');
    }
}
