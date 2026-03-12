@extends('layouts.app')

@section('title', 'Nueva Inscripción')

@section('content')

<livewire:inscripcion-curso.inscripcion-curso-create :feligresId="$instructor->feligres->id" />

@endsection