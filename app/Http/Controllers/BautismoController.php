<?php

namespace App\Http\Controllers;

use App\Models\Bautismo;
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

    public function show(Bautismo $bautismo)
    {
        return view('bautismo.show', compact('bautismo'));
    }

    public function edit(Bautismo $bautismo)
    {
        return view('bautismo.edit', compact('bautismo'));
    }
}
