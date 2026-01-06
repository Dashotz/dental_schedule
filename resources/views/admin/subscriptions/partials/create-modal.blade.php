<x-modal id="createSubscriptionModal" title="Create New Subscription" size="lg">
    <form id="createSubscriptionForm">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="modal_subdomain_id" class="form-label flex items-center gap-2">
                    <i class="bi bi-globe text-dental-teal"></i>Subdomain <span class="text-red-500">*</span>
                </label>
                <select class="input-dental" id="modal_subdomain_id" name="subdomain_id" required>
                    <option value="">Select Subdomain...</option>
                    @foreach($subdomains as $subdomain)
                        <option value="{{ $subdomain->id }}">
                            {{ $subdomain->name }} ({{ $subdomain->subdomain }})
                        </option>
                    @endforeach
                </select>
                <div class="text-red-500 text-sm mt-1 hidden" id="subdomain_id_error"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="modal_plan_name" class="form-label flex items-center gap-2">
                        <i class="bi bi-star text-dental-teal"></i>Plan <span class="text-red-500">*</span>
                    </label>
                    <select class="input-dental" id="modal_plan_name" name="plan_name" required>
                        <option value="">Select Plan...</option>
                        <option value="basic">Basic</option>
                        <option value="premium">Premium</option>
                        <option value="enterprise">Enterprise</option>
                    </select>
                    <div class="text-red-500 text-sm mt-1 hidden" id="plan_name_error"></div>
                </div>
                <div>
                    <label for="modal_amount" class="form-label flex items-center gap-2">
                        <i class="bi bi-currency-dollar text-dental-teal"></i>Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <span class="bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg px-4 py-2.5 text-gray-600 flex items-center">$</span>
                        <input type="number" step="0.01" class="input-dental rounded-l-none" 
                               id="modal_amount" name="amount" required>
                    </div>
                    <div class="text-red-500 text-sm mt-1 hidden" id="amount_error"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="modal_billing_cycle" class="form-label flex items-center gap-2">
                        <i class="bi bi-calendar-range text-dental-teal"></i>Billing Cycle <span class="text-red-500">*</span>
                    </label>
                    <select class="input-dental" id="modal_billing_cycle" name="billing_cycle" required>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                    <div class="text-red-500 text-sm mt-1 hidden" id="billing_cycle_error"></div>
                </div>
                <div>
                    <label for="modal_start_date" class="form-label flex items-center gap-2">
                        <i class="bi bi-calendar-check text-dental-teal"></i>Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" class="input-dental" 
                           id="modal_start_date" name="start_date" 
                           value="{{ date('Y-m-d') }}" required>
                    <div class="text-red-500 text-sm mt-1 hidden" id="start_date_error"></div>
                </div>
            </div>

            <div>
                <label for="modal_end_date" class="form-label flex items-center gap-2">
                    <i class="bi bi-calendar-x text-dental-teal"></i>End Date (Auto-calculated)
                </label>
                <input type="date" class="input-dental bg-gray-100 cursor-not-allowed" 
                       id="modal_end_date" name="end_date" readonly>
                <small class="text-gray-500 block mt-2">
                    <i class="bi bi-info-circle"></i> 
                    End date is automatically calculated based on billing cycle and start date.
                </small>
            </div>
        </div>
    </form>
    
    <x-slot name="footer">
        <button type="button" class="btn-dental-outline" onclick="closeModal('createSubscriptionModal')">
            <i class="bi bi-x-circle"></i> Cancel
        </button>
        <button type="submit" form="createSubscriptionForm" class="btn-dental bg-green-500 hover:bg-green-600">
            <i class="bi bi-check-circle"></i> Create Subscription
        </button>
    </x-slot>
</x-modal>

