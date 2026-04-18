@extends('layouts.app')

@section('title', 'Editar Tipo de Curso')

@section('content')
    <livewire:tipo-curso.tipo-curso-edit :tipocurso="$tipocurso" />
@endsection
