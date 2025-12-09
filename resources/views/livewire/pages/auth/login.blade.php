<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div class="max-w-md mx-auto mt-10 p-6 bg-white shadow rounded">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <label for="remember" class="ml-2 block text-sm text-gray-600">{{ __('Remember me') }}</label>
        </div>

        <!-- Button + Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <x-primary-button class="px-4 py-2 text-sm">
                {{ __('Log in') }}
            </x-primary-button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-indigo-600">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-right mt-2">
                <a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-indigo-600">
                    {{ __("Don't have an account? Register") }}
                </a>
            </div>
        @endif
    </form>
</div>
