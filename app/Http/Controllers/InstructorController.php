<?php

namespace App\Http\Controllers;

use App\Models\Instructor;

class InstructorController extends Controller
{
    public function dashboard()
    {
        return view('Instructor.dashboard');
    }

    public function index()
    {
        return view('Instructor.index');
    }

    public function create()
    {
        return view('Instructor.create');
    }

    public function show(Instructor $instructor)
    {
        return view('Instructor.show', compact('instructor'));
    }

    public function edit(Instructor $instructor)
    {
        return view('Instructor.edit', compact('instructor'));
    }
}