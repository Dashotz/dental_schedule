@extends('layouts.app')

@section('title', 'Doctor Login - Dental System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-dental-teal-lighter via-white to-dental-teal-lighter flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-dental-teal opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-dental-teal-dark opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-dental-teal opacity-3 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Login Card -->
        <div class="card-dental shadow-2xl border-0">
            <div class="card-dental-header relative overflow-hidden">
                <!-- Decorative pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full -ml-12 -mb-12"></div>
                </div>
                <h4 class="text-xl font-semibold flex items-center gap-3 relative z-10">
                    <x-dental-icon name="box-arrow-in-right" class="w-6 h-6" /> 
                    <span>Doctor Login</span>
                </h4>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('doctor.login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <x-dental-icon name="envelope" class="w-4 h-4 text-dental-teal" />
                            <span>Email Address</span>
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   class="input-dental pl-11 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Enter your email"
                                   required 
                                   autofocus>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-dental-icon name="envelope" class="w-5 h-5 text-gray-400" />
                            </div>
                        </div>
                        @error('email')
                            <div class="mt-2 flex items-center gap-2 text-red-500 text-sm">
                                <x-dental-icon name="exclamation-triangle" class="w-4 h-4" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                            <x-dental-icon name="shield-check" class="w-4 h-4 text-dental-teal" />
                            <span>Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   class="input-dental pl-11 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-dental-icon name="shield-check" class="w-5 h-5 text-gray-400" />
                            </div>
                        </div>
                        @error('password')
                            <div class="mt-2 flex items-center gap-2 text-red-500 text-sm">
                                <x-dental-icon name="exclamation-triangle" class="w-4 h-4" />
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   class="w-4 h-4 text-dental-teal border-gray-300 rounded focus:ring-dental-teal focus:ring-2" 
                                   id="remember" 
                                   name="remember">
                            <label class="ml-2 text-sm text-gray-700 cursor-pointer" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-dental w-full flex items-center justify-center gap-3 py-3 text-base font-semibold">
                        <x-dental-icon name="box-arrow-in-right" class="w-5 h-5" />
                        <span>Sign In as Doctor</span>
                    </button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="px-8 py-5 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center justify-center gap-2 text-gray-600 text-sm">
                    <x-dental-icon name="info-circle" class="w-4 h-4 text-dental-teal" />
                    <span class="text-center">Only authorized doctors can access this system</span>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Need help? 
                <a href="#" class="text-dental-teal hover:text-dental-teal-dark font-semibold transition-colors">
                    Contact Support
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

