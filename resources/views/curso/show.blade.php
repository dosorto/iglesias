@extends('layouts.app')

@section('title', 'Detalle Curso')

@section('content')
    <livewire:curso.curso-show :curso="$curso" />
@endsection