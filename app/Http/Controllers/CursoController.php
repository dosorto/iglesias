<?php

namespace App\Http\Controllers;

use App\Models\Curso;

class CursoController extends Controller
{
    public function index()
    {
        return view('curso.index');
    }

    public function create()
    {
        return view('curso.create');
    }

    public function show(Curso $curso)
    {
        return view('curso.show', compact('curso'));
    }

    public function edit(Curso $curso)
    {
        return view('curso.edit', compact('curso'));
    }
}