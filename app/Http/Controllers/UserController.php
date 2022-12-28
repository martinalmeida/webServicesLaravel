<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;


class UserController extends Controller
{
    public function index()
    {
        return view('users.users');
    }


    public function dataTableUser(Request $request)
    {
        if ($request->ajax()) {
            $data = User::join('roles', 'roles.id', '=', 'users.rolId')
                ->get(['users.id', 'roles.rol', 'users.name', 'users.email']);
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $butons = '<a onclick="window.alert(' . $row['id'] . ');" class="btn btn-primary btn-sm">Editar</a>';
                    return $butons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tablaUsers');
    }
}
