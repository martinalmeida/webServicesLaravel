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
                ->get(['usersplacas.id', 'users.name', 'usersplacas.placaId', 'status.status']);
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $butons = '<a onclick="window.alert(' . $row['id'] . ');" class="btn btn-primary btn-sm">Asignar</a>';
                    $butons .= '<a onclick="window.alert(' . $row['id'] . ');" class="btn btn-info btn-sm">Estado</a>';
                    return $butons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('asignar');
    }
}
