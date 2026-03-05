@extends('layouts.app')

@section('title', 'Editar Encargado')

@section('content')
<livewire:encargado.encargado-edit :encargado="$encargado" />
@endsection
