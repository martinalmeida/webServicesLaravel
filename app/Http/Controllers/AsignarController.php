<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignar;
use App\Models\User;
use App\Models\Placa;
use DataTables;


class AsignarController extends Controller
{
    public function index()
    {
        if (auth()->user()->rolId == 1) {
            return view('asignar.asignar', ['routeActive' => 'asignar']);
        } else {
            return view('home.home', ['routeActive' => 'home']);
        }
    }

    public function dataTableAsignar(Request $request)
    {
        if ($request->ajax()) {
            $data = Asignar::join('users', 'users.id', '=', 'usersplacas.userId')
                ->join('status', 'status.id', '=', 'usersplacas.status')
                ->join('maquinarias', 'maquinarias.id', '=', 'usersplacas.placaId')
                ->whereIn('usersplacas.status', array(1, 2))
                ->where('usersplacas.nit', '=', auth()->user()->nit)
                ->orderBy('usersplacas.id', 'desc')
                ->get(['usersplacas.id', 'users.name', 'maquinarias.placa', 'status.status', 'usersplacas.status AS estado']);
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $butons = '<div class="text-center"><a onclick="listData(' . $row['id'] . ');" class="btn btn-success btn-sm text-white" title="Editar Usuario"><i class="fal fa-user-edit"></i></a> ';
                    $butons .= '<a onclick="statusChange(' . $row['id'] . ', ' . $row['estado'] . ');" class="btn btn-primary btn-sm text-white" title="Cambiar Estado"><i class="fal fa-exchange-alt"></i></a> ';
                    $butons .= '<a onclick="deleteRegister(' . $row['id'] . ');" class="btn btn-danger btn-sm text-white" title="Eliminar Usuario"><i class="fal fa-trash"></i></a></div>';
                    return $butons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('asignar');
    }

    public function selectUsers(Request $request)
    {
        if ($request->ajax()) {
            $placas = User::select('id', 'name')
                ->where('status', '=', 1)
                ->where('nit', '=', auth()->user()->nit)
                ->get();
        }
        return response()->json($placas);
    }

    public function selectPlaca(Request $request)
    {
        if ($request->ajax()) {
            $placas = Placa::select('id', 'placa')
                ->where('status', '=', 1)
                ->where('nit', '=', auth()->user()->nit)
                ->get();
        }
        return response()->json($placas);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $create = Asignar::create([
                'userId' => $request->usuario,
                'placaId' => $request->placa
            ]);
        }
        $create ? $response = ['status' => true] : $response = ['status' => false];
        return response()->json($response);
    }

    public function selectAsigne($id)
    {
        $asigner = Asignar::where('id', $id)->get();
        return response()->json($asigner);
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {
            $update = Asignar::where('id', $request->idAsigne)
                ->limit(1)->update(['userId' => $request->usuario, 'placaId' => $request->placa]);
        }
        $update ? $response = ['status' => true] : $response = ['status' => false];
        return response()->json($response);
    }

    public function status($id, $status)
    {
        $status == 1 ? $change = 2 : $change = 1;
        $update = Asignar::where('id', $id)
            ->limit(1)->update(['status' => $change]);
        $update ? $response = ['status' => true] : $response = ['status' => false];
        return response()->json($response);
    }

    public function delete($id)
    {
        $update = Asignar::where('id', $id)
            ->limit(1)->update(['status' => 3]);
        $update ? $response = ['status' => true] : $response = ['status' => false];
        return response()->json($response);
    }
}
