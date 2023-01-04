<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class informeController extends Controller
{
    public function index()
    {
        return view('informes.informes');
    }
}
