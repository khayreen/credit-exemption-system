@extends('layouts.app')
@section('title', __('Forbidden'))
@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold">403</h1>
        <p class="fs-3"> <span class="text-danger">Access Denied!</span> Forbidden.</p>
        <p class="lead">
            You do not have the required permissions to view this page.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go Home</a>
    </div>
</div>
@endsection