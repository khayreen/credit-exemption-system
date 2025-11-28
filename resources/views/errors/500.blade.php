@extends('layouts.app')
@section('title', __('Server Error'))
@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold">500</h1>
        <p class="fs-3"> <span class="text-danger">Opps!</span> Server Error.</p>
        <p class="lead">
            Something went wrong on our end. We are working to fix it.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
    </div>
</div>
@endsection