<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignar;
use DataTables;


class AsignarController extends Controller
{
    public function index()
    {
        return view('asignar.asignar');
    }


    public function dataTableAsignar(Request $request)
    {
        if ($request->ajax()) {
            $data = Asignar::join('users', 'users.id', '=', 'usersplacas.userId')
                ->join('status', 'status.id', '=', 'usersplacas.status')
                ->join('maquinarias', 'maquinarias.id', '=', 'usersplacas.placaId')
                ->get(['usersplacas.id', 'users.name', 'maquinarias.placa', 'status.status']);
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
}
