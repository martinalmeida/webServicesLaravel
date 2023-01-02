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
                ->join('status', 'status.id', '=', 'users.status')
                ->whereIn('users.status', array(1, 2))
                ->orderBy('users.id', 'desc')
                ->get(['users.id', 'roles.rol', 'users.name', 'users.email', 'status.status', 'users.status AS estado']);
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $butons = '<div class="text-center"><a onclick="window.alert(' . $row['id'] . ');" class="btn btn-success btn-sm text-white" title="Editar Usuario"><i class="fal fa-user-edit"></i></a> ';
                    $butons .= '<a onclick="statusChange(' . $row['id'] . ', ' . $row['estado'] . ');" class="btn btn-primary btn-sm text-white" title="Cambiar Estado"><i class="fal fa-exchange-alt"></i></a> ';
                    $butons .= '<a onclick="window.alert(' . $row['id'] . ');" class="btn btn-danger btn-sm text-white" title="Eliminar Usuario"><i class="fal fa-trash"></i></a></div>';
                    return $butons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tablaUsers');
    }

    public function status($id, $status)
    {
        $status == 1 ? $change = 2 : $change = 1;
        $update = User::where('id', $id)
            ->limit(1)->update(['status' => $change]);
        $update ? $response = ['status' => true] : $response = ['status' => false];
        return response()->json($response);
    }
}