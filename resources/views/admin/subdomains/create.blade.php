@extends('layouts.app')

@section('title', 'Create Subdomain')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-plus-circle text-primary"></i> Create New Subdomain</h2>
        <p class="text-muted mb-0">Add a new dental clinic subdomain to the system</p>
    </div>
    <a href="{{ route('admin.subdomains.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-10 col-xl-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-globe"></i> Subdomain Information</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.subdomains.store') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="subdomain" class="form-label fw-semibold">
                            <i class="bi bi-globe me-1"></i>Subdomain <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="text" 
                                   class="form-control @error('subdomain') is-invalid @enderror" 
                                   id="subdomain" 
                                   name="subdomain" 
                                   value="{{ old('subdomain') }}" 
                                   placeholder="clinic-name" 
                                   required>
                            <span class="input-group-text bg-light">.yourdomain.com</span>
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Only lowercase letters, numbers, and hyphens allowed.
                        </small>
                        @error('subdomain')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-building me-1"></i>Clinic Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Enter clinic name"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-file-text me-1"></i>Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Enter a brief description about the clinic">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="clinic@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="phone" class="form-label fw-semibold">
                                <i class="bi bi-telephone me-1"></i>Phone
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="+1 (555) 123-4567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold">
                            <i class="bi bi-geo-alt me-1"></i>Address
                        </label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2"
                                  placeholder="Enter clinic address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-check-circle"></i> Create Subdomain
                        </button>
                        <a href="{{ route('admin.subdomains.index') }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

