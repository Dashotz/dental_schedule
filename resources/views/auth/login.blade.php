@extends('layouts.app')

@section('title', 'Login - Dental System')

@section('content')
<div class="flex justify-center items-center min-h-[60vh]">
    <div class="w-full max-w-md">
        <div class="card-dental shadow-xl">
            <div class="card-dental-header">
                <h4 class="text-xl font-semibold flex items-center gap-2">
                    <x-dental-icon name="box-arrow-in-right" class="w-5 h-5" /> Login
                </h4>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" class="input-dental @error('email') border-red-500 @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" class="input-dental @error('password') border-red-500 @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 flex items-center">
                        <input type="checkbox" class="w-4 h-4 text-dental-teal border-gray-300 rounded focus:ring-dental-teal" id="remember" name="remember">
                        <label class="ml-2 text-sm text-gray-700" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn-dental w-full">
                        <x-dental-icon name="box-arrow-in-right" class="w-5 h-5" /> Login
                    </button>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 text-center text-gray-500 text-sm">
                <small>Only doctors and staff can access this system</small>
            </div>
        </div>
    </div>
</div>
@endsection
