@extends('layouts.app')

@section('title', 'Detalle de Encargado')

@section('content')
<livewire:encargado.encargado-show :encargado="$encargado" />
@endsection
