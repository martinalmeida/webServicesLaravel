<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use DataTables;

class RolController extends Controller
{
    public function index()
    {
        return view('roles.roles');
    }


    public function dataTableRol(Request $request)
    {
        if ($request->ajax()) {
            $data = Rol::select('id', 'rol', 'descripcion')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $butons = '<a onclick="window.alert(' . $row['id'] . ');" class="btn btn-primary btn-sm">Editar</a>';
                    return $butons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tablaRoles');
    }
}
