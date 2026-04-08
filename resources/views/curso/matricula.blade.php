@extends('layouts.app')

@section('title', 'Detalle Matriculado')

@section('content')
    <livewire:curso.matriculado-curso-show :inscripcionId="$inscripcionId" />
@endsection