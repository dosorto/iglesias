@extends('layouts.app')

@section('title', 'Editar Instructor')

@section('content')
    <livewire:instructor.instructor-edit :instructor="$instructor" />
@endsection