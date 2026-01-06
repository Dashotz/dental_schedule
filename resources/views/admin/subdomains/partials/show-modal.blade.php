<x-modal id="viewSubdomainModal" title="{{ $subdomain->name }}" size="xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-1 block flex items-center gap-1.5">
                <x-dental-icon name="globe" class="w-4 h-4 flex-shrink-0" />Subdomain
            </label>
            <strong class="block text-gray-900">{{ $subdomain->subdomain }}.helioho.st</strong>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-1 block flex items-center gap-1">
                <x-dental-icon name="building" class="w-4 h-4" />Clinic Name
            </label>
            <strong class="block text-gray-900">{{ $subdomain->name }}</strong>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-1 block flex items-center gap-1">
                <x-dental-icon name="envelope" class="w-4 h-4" />Email
            </label>
            <span class="block text-gray-900">
                @if($subdomain->email)
                    {{ $subdomain->email }}
                @else
                    <span class="text-gray-400 italic">N/A</span>
                @endif
            </span>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-1 block flex items-center gap-1">
                <x-dental-icon name="telephone" class="w-4 h-4" />Phone
            </label>
            <span class="block text-gray-900">
                @if($subdomain->phone)
                    {{ $subdomain->phone }}
                @else
                    <span class="text-gray-400 italic">N/A</span>
                @endif
            </span>
        </div>
        @if($subdomain->address)
        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-1 block flex items-center gap-1">
                <x-dental-icon name="geo-alt" class="w-4 h-4" />Address
            </label>
            <span class="block text-gray-900">{{ $subdomain->address }}</span>
        </div>
        @endif
        @if($subdomain->description)
        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-1 block flex items-center gap-1">
                <x-dental-icon name="file-text" class="w-4 h-4" />Description
            </label>
            <span class="block text-gray-900">{{ $subdomain->description }}</span>
        </div>
        @endif
        <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
            <label class="text-gray-500 text-xs mb-2 block flex items-center gap-1.5">
                <x-dental-icon name="toggle-on" class="w-4 h-4 flex-shrink-0" />Status
            </label>
            <div class="flex items-center gap-3">
                <div class="form-check form-switch">
                    <input class="form-check-input toggle-status" 
                           type="checkbox" 
                           data-id="{{ $subdomain->id }}"
                           {{ $subdomain->is_active ? 'checked' : '' }}
                           style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
                </div>
                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium {{ $subdomain->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <x-dental-icon name="{{ $subdomain->is_active ? 'check-circle' : 'x-circle' }}" class="w-3 h-3" />
                    {{ $subdomain->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card-dental">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-3 rounded-t-2xl">
                <h6 class="font-semibold flex items-center gap-2">
                    <x-dental-icon name="lightning-charge" class="w-5 h-5" /> Quick Actions
                </h6>
            </div>
            <div class="p-4 space-y-2">
                <button class="inline-flex items-center justify-center gap-2 btn-dental w-full" id="generateLinkBtnModal" data-subdomain-id="{{ $subdomain->id }}">
                    <x-dental-icon name="link-45deg" class="w-5 h-5" /> Generate Registration Link
                </button>
                <button class="inline-flex items-center justify-center gap-2 btn-dental-outline w-full border-yellow-500 text-yellow-600 hover:bg-yellow-50" id="editSubdomainBtnModal" data-subdomain-id="{{ $subdomain->id }}">
                    <x-dental-icon name="pencil" class="w-5 h-5" /> Edit Subdomain
                </button>
            </div>
        </div>

        <div class="card-dental">
            <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-4 py-3 rounded-t-2xl">
                <h6 class="font-semibold flex items-center gap-2">
                    <x-dental-icon name="link-45deg" class="w-5 h-5" /> Registration Links
                </h6>
            </div>
            <div class="p-4">
                @php
                    $activeLink = $subdomain->registrationLinks->where('is_active', true)->first();
                @endphp
                @if($activeLink)
                    <div class="mb-3">
                        <label class="form-label text-xs text-gray-500 mb-2 block">Active Registration Link</label>
                        <div class="flex">
                            <input type="text" 
                                   class="input-dental rounded-r-none" 
                                   value="{{ $activeLink->link }}" 
                                   readonly
                                   id="registrationLinkInputModal">
                            <button class="inline-flex items-center justify-center btn-dental-outline rounded-l-none border-l-0 copy-link-btn" 
                                    type="button" 
                                    data-link="{{ $activeLink->link }}"
                                    title="Copy to clipboard">
                                <x-dental-icon name="clipboard" class="w-4 h-4" />
                            </button>
                        </div>
                        <div class="mt-2 space-y-1">
                            <small class="text-gray-500 block flex items-center gap-1">
                                <x-dental-icon name="info-circle" class="w-4 h-4" /> 
                                Uses: <strong>{{ $activeLink->used_count }}</strong>/∞ (Unlimited)
                            </small>
                            @if($activeLink->expires_at)
                                <small class="text-gray-500 block flex items-start gap-1">
                                    <x-dental-icon name="calendar-x" class="w-4 h-4 flex-shrink-0 mt-0.5" /> 
                                    <span>Expires: <strong>{{ $activeLink->expires_at->format('M d, Y') }}</strong><br><span class="text-gray-400 text-xs">(when subscription ends)</span></span>
                                </small>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <x-dental-icon name="link-45deg" class="w-12 h-12 text-gray-300 mx-auto" />
                        <p class="text-gray-500 mb-0 mt-2">No active registration link</p>
                        <small class="text-gray-400">Generate one to get started</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <x-slot name="footer">
        <button type="button" class="inline-flex items-center gap-2 btn-dental-outline" onclick="closeModal('viewSubdomainModal')">
            <x-dental-icon name="x-circle" class="w-5 h-5" /> Close
        </button>
    </x-slot>
</x-modal>

