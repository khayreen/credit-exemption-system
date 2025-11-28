@extends('layouts.app')
@section('title', __('Service Unavailable'))
@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold">503</h1>
        <p class="fs-3"> <span class="text-danger">Opps!</span> Service Unavailable.</p>
        <p class="lead">
            The application is currently down for maintenance. Please check back later.
        </p>
    </div>
</div>
@endsection