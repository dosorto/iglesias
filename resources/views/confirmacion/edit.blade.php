@extends('layouts.app')
@section('content')
    @livewire('confirmacion.confirmacion-edit', ['confirmacion' => $confirmacion])
@endsection