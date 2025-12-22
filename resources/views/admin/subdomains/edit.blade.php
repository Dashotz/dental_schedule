@extends('layouts.app')

@section('title', 'Edit Subdomain')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-pencil text-warning"></i> Edit Subdomain</h2>
        <p class="text-muted mb-0">Update subdomain information for {{ $subdomain->name }}</p>
    </div>
    <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-white py-3">
                <h5 class="mb-0"><i class="bi bi-globe me-2"></i> Subdomain Information</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.subdomains.update', $subdomain) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="subdomain" class="form-label fw-semibold mb-2">
                                <i class="bi bi-globe text-primary me-1"></i>Subdomain <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control form-control-lg @error('subdomain') is-invalid @enderror" 
                                       id="subdomain" 
                                       name="subdomain" 
                                       value="{{ old('subdomain', $subdomain->subdomain) }}" 
                                       placeholder="clinic-name" 
                                       required>
                                <span class="input-group-text bg-light border-start-0">.helioho.st</span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Only lowercase letters, numbers, and hyphens allowed.
                            </small>
                            @error('subdomain')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label fw-semibold mb-2">
                                <i class="bi bi-building text-primary me-1"></i>Clinic Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $subdomain->name) }}" 
                                   placeholder="Enter clinic name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label for="description" class="form-label fw-semibold mb-2">
                                <i class="bi bi-file-text text-primary me-1"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="Enter a brief description about the clinic">{{ old('description', $subdomain->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for="email" class="form-label fw-semibold mb-2">
                                <i class="bi bi-envelope text-primary me-1"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $subdomain->email) }}"
                                   placeholder="clinic@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for="phone" class="form-label fw-semibold mb-2">
                                <i class="bi bi-telephone text-primary me-1"></i>Phone
                            </label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $subdomain->phone) }}"
                                   placeholder="+1 (555) 123-4567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for="address" class="form-label fw-semibold mb-2">
                                <i class="bi bi-geo-alt text-primary me-1"></i>Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Enter clinic address">{{ old('address', $subdomain->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-4 mt-4 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Update Subdomain
                        </button>
                        <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-secondary btn-lg px-4">
                            <i class="bi bi-x-circle me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

