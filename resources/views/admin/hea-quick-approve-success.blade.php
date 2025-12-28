@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">✅ HEA Registration Approved</h3>
                </div>

                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>

                    <h4 class="mt-4">Success!</h4>

                    <p class="lead">
                        <strong>{{ $user->name }}</strong> has been approved as HEA Personnel.
                    </p>

                    <div class="alert alert-info mt-4">
                        <h6>📧 Notification Sent</h6>
                        <p class="mb-0">An approval email has been sent to <strong>{{ $user->email }}</strong> with login instructions.</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ url('/login') }}" class="btn btn-primary">
                            <i class="bi bi-house-door"></i> Go to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
