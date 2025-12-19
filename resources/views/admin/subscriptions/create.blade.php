@extends('layouts.app')

@section('title', 'Create Subscription')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Create Subscription for {{ $subdomain->name }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.subscriptions.store', $subdomain->id) }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="plan_name" class="form-label">Plan <span class="text-danger">*</span></label>
                            <select class="form-select @error('plan_name') is-invalid @enderror" 
                                    id="plan_name" name="plan_name" required>
                                <option value="">Select Plan...</option>
                                <option value="basic" {{ old('plan_name') == 'basic' ? 'selected' : '' }}>Basic</option>
                                <option value="premium" {{ old('plan_name') == 'premium' ? 'selected' : '' }}>Premium</option>
                                <option value="enterprise" {{ old('plan_name') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                            </select>
                            @error('plan_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount') }}" required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                            <select class="form-select @error('billing_cycle') is-invalid @enderror" 
                                    id="billing_cycle" name="billing_cycle" required>
                                <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('billing_cycle') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('billing_cycle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date (Auto-calculated)</label>
                        <input type="date" class="form-control" 
                               id="end_date" name="end_date" readonly 
                               style="background-color: #e9ecef; cursor: not-allowed;">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            End date is automatically calculated based on billing cycle and start date.
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Create Subscription
                        </button>
                        <a href="{{ route('admin.subdomains.show', $subdomain) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-calculate end date based on billing cycle and start date
        function calculateEndDate() {
            const billingCycle = $('#billing_cycle').val();
            const startDate = $('#start_date').val();
            
            if (!startDate || !billingCycle) {
                $('#end_date').val('');
                return;
            }
            
            const start = new Date(startDate);
            let endDate = new Date(start);
            
            switch(billingCycle) {
                case 'monthly':
                    endDate.setMonth(endDate.getMonth() + 1);
                    endDate.setDate(endDate.getDate() - 1); // Subtract 1 day
                    break;
                case 'quarterly':
                    endDate.setMonth(endDate.getMonth() + 3);
                    endDate.setDate(endDate.getDate() - 1); // Subtract 1 day
                    break;
                case 'yearly':
                    endDate.setFullYear(endDate.getFullYear() + 1);
                    endDate.setDate(endDate.getDate() - 1); // Subtract 1 day
                    break;
            }
            
            $('#end_date').val(endDate.toISOString().split('T')[0]);
        }
        
        // Calculate on page load if values exist
        calculateEndDate();
        
        // Calculate when billing cycle or start date changes
        $('#billing_cycle, #start_date').on('change', calculateEndDate);
    });
</script>
@endpush
@endsection

