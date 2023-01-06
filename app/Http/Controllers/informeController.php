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
            $data = Alquiler::join('users', 'users.id', '=', 'usersplacas.userId')
                ->join('status', 'status.id', '=', 'usersplacas.status')
                ->join('maquinarias', 'maquinarias.id', '=', 'usersplacas.placaId')
                ->whereIn('usersplacas.status', array(1, 2))
                ->where('usersplacas.nit', '=', auth()->user()->nit)
                ->orderBy('usersplacas.id', 'desc')
                ->get(['usersplacas.id', 'users.name', 'maquinarias.placa', 'status.status', 'usersplacas.status AS estado']);

            return Datatables::of($data)->addIndexColumn()->make(true);
        }
        return view('informeAlquiler');
    }
}
