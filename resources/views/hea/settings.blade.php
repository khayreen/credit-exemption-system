@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('hea.dashboard') }}" class="btn btn-light mb-3"><< Back to Dashboard</a>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>System Settings</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('hea.settings.update') }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="row">
                            @foreach($settings as $setting)
                                <div class="col-md-12 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-light py-2">
                                            <strong>{{ ucwords(str_replace('_', ' ', $setting->key)) }}</strong>
                                            @if($setting->description)
                                                <small class="text-muted d-block">{{ $setting->description }}</small>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            @if($setting->type === 'textarea')
                                                <textarea name="{{ $setting->key }}" 
                                                          class="form-control" 
                                                          rows="8" 
                                                          placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}">{{ $setting->value }}</textarea>
                                            @elseif($setting->type === 'url')
                                                <input type="url" 
                                                       name="{{ $setting->key }}" 
                                                       class="form-control" 
                                                       value="{{ $setting->value }}"
                                                       placeholder="https://example.com">
                                                <div class="form-text">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    <a href="{{ $setting->value }}" target="_blank" rel="noopener">Test link</a>
                                                </div>
                                            @else
                                                <input type="text" 
                                                       name="{{ $setting->key }}" 
                                                       class="form-control" 
                                                       value="{{ $setting->value }}"
                                                       placeholder="Enter {{ strtolower(str_replace('_', ' ', $setting->key)) }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body text-center py-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Changes to these settings will affect the entire system. Please review carefully before saving.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection