@extends('layouts.app')
@section('title', __('Too Many Requests'))
@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold">429</h1>
        <p class="fs-3"> <span class="text-danger">Opps!</span> Too Many Requests.</p>
        <p class="lead">
            You have made too many requests in a short period. Please wait and try again later.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
    </div>
</div>
@endsection