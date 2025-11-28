@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Profile</h2>
    <hr>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/150" alt="Default Profile Photo" class="img-fluid rounded-circle mb-3">
                        @endif
                        <input type="file" name="photo" class="form-control">
                        <small class="form-text text-muted">Select a new photo to update.</small>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                            <small class="form-text text-muted">Email cannot be changed.</small>
                        </div>
                         <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
