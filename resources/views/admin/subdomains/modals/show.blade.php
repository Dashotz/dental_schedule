<div class="modal fade" id="viewSubdomainModal" tabindex="-1" aria-labelledby="viewSubdomainModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewSubdomainModalLabel">
                    <i class="bi bi-globe me-2"></i>{{ $subdomain->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="viewSubdomainModalBody">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-globe me-1"></i>Subdomain
                            </label>
                            <strong class="d-block">{{ $subdomain->subdomain }}.yourdomain.com</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-building me-1"></i>Clinic Name
                            </label>
                            <strong class="d-block">{{ $subdomain->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-envelope me-1"></i>Email
                            </label>
                            <span class="d-block">
                                @if($subdomain->email)
                                    {{ $subdomain->email }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-telephone me-1"></i>Phone
                            </label>
                            <span class="d-block">
                                @if($subdomain->phone)
                                    {{ $subdomain->phone }}
                                @else
                                    <span class="text-muted fst-italic">N/A</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($subdomain->address)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-geo-alt me-1"></i>Address
                            </label>
                            <span class="d-block">{{ $subdomain->address }}</span>
                        </div>
                    </div>
                    @endif
                    @if($subdomain->description)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-1 d-block">
                                <i class="bi bi-file-text me-1"></i>Description
                            </label>
                            <span class="d-block">{{ $subdomain->description }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <label class="text-muted small mb-2 d-block">
                                <i class="bi bi-toggle-on me-1"></i>Status
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" 
                                           type="checkbox" 
                                           data-id="{{ $subdomain->id }}"
                                           {{ $subdomain->is_active ? 'checked' : '' }}
                                           style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
                                </div>
                                <span class="badge bg-{{ $subdomain->is_active ? 'success' : 'danger' }} px-3 py-2">
                                    <i class="bi bi-{{ $subdomain->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-lightning-charge"></i> Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary w-100 mb-2" id="generateLinkBtnModal" data-subdomain-id="{{ $subdomain->id }}">
                                    <i class="bi bi-link-45deg"></i> Generate Registration Link
                                </button>
                                <button class="btn btn-warning w-100" id="editSubdomainBtnModal" data-subdomain-id="{{ $subdomain->id }}">
                                    <i class="bi bi-pencil"></i> Edit Subdomain
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="bi bi-link-45deg"></i> Registration Links</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $activeLink = $subdomain->registrationLinks->where('is_active', true)->first();
                                @endphp
                                @if($activeLink)
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-2">Active Registration Link</label>
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control registration-link-input" 
                                                   value="{{ $activeLink->link }}" 
                                                   readonly
                                                   id="registrationLinkInputModal">
                                            <button class="btn btn-outline-primary copy-link-btn" 
                                                    type="button" 
                                                    data-link="{{ $activeLink->link }}"
                                                    title="Copy to clipboard">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">
                                                <i class="bi bi-info-circle"></i> 
                                                Uses: <strong>{{ $activeLink->used_count }}</strong>/∞ (Unlimited)
                                            </small>
                                            @if($activeLink->expires_at)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="bi bi-calendar-x"></i> 
                                                    Expires: <strong>{{ $activeLink->expires_at->format('M d, Y') }}</strong>
                                                    <br><span class="text-muted small">(when subscription ends)</span>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="bi bi-link-45deg display-6 text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">No active registration link</p>
                                        <small class="text-muted">Generate one to get started</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

