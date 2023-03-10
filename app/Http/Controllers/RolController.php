<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use DataTables;

class RolController extends Controller
{
    public function index()
    {
        if (auth()->user()->rolId == 1) {
            return view('roles.roles', ['routeActive' => 'roles']);
        } else {
            return view('home.home', ['routeActive' => 'home']);
        }
    }

    public function dataTableRol(Request $request)
    {
        if ($request->ajax()) {
            $data = Rol::select('id', 'rol', 'descripcion')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $butons = '<div class="text-center"><a onclick="visualizar(' . $row['id'] . ');" class="btn btn-info btn-sm text-white" title="Eliminar Usuario"><i class="fal fa-eye"></i></a></div>';
                    return $butons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tablaRoles');
    }

    public function selectRolId($id)
    {
        $user = Rol::where('id', $id)->get();
        return response()->json($user);
    }
}
