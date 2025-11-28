@extends('layouts.app')
@section('title', __('Unauthorized'))
@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold">401</h1>
        <p class="fs-3"> <span class="text-danger">Opps!</span> Unauthorized.</p>
        <p class="lead">
            You are not authorized to access this page. Please log in.
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
    </div>
</div>
@endsection