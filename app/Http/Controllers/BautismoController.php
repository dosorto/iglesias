<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BautismoController extends Controller
{
    public function index()
    {
        return view('bautismo.index');
    }

    public function create()
    {
        return view('bautismo.create');
    }
}
