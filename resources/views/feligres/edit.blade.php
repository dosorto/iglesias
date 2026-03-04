@extends('layouts.app')

@section('title', 'Editar Feligrés')

@section('content')
    <livewire:feligres.feligres-edit :feligre="$feligre" />
@endsection
