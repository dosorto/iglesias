@extends('layouts.app')

@section('title', 'Editar Bautismo')

@section('content')
    <livewire:bautismo.bautismo-edit :bautismo="$bautismo" />
@endsection
