@extends('layouts.app')

@section('title', 'Detalle de Bautismo')

@section('content')
    <livewire:bautismo.bautismo-show :bautismo="$bautismo" />
@endsection
