@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-life-ring me-2"></i>Help & Support
                    </h5>
                </div>
                <div class="card-body">
                    <p>This page is under development. Help and support resources will be available soon.</p>
                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection