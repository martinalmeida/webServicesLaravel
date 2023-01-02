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
            return Datatables::of($data)->addIndexColumn()->make(true);
        }
        return view('tablaRoles');
    }

    public function selectRol(Request $request)
    {
        if ($request->ajax()) {
            $roles = Rol::select('id', 'rol')->get();
        }
        return response()->json($roles);
    }
}
