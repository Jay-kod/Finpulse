@extends('layouts.guest')

@section('title', '| User Registration')
@section('subtitle', 'Create a User Account')
@section('left_bg_gradient', 'from-blue-600/30 to-blue-900/40')

@section('left_panel_content')
    <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight text-white">Join as a Stakeholder</h2>
    <p class="text-lg text-gray-300 leading-relaxed mb-10 font-medium">
        Create your account to start monitoring financial app sentiment. As a User, you'll have access to high-level dashboards, visual reports, and performance tracking.
    </p>
    <div class="space-y-4">
        <div class="flex items-center space-x-5 bg-white/5 p-5 rounded-2xl border border-white/5 backdrop-blur-md">
            <div class="w-14 h-14 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-lg">Sentiment Overview</h4>
                <p class="text-gray-400 font-medium">Track positive, neutral, and negative trends across platforms.</p>
            </div>
        </div>
        <div class="flex items-center space-x-5 bg-white/5 p-5 rounded-2xl border border-white/5 backdrop-blur-md">
            <div class="w-14 h-14 bg-cyan-500/20 rounded-xl flex items-center justify-center text-cyan-400 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-lg">Feature Requests</h4>
                <p class="text-gray-400 font-medium">See what your users want built next in real-time.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Full Name') }}</label>
            <x-ui.input id="name" class="block w-full py-3 px-4 bg-gray-50 dark:bg-gray-900/50" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Email Address') }}</label>
            <x-ui.input id="email" class="block w-full py-3 px-4 bg-gray-50 dark:bg-gray-900/50" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password & Confirm --}}
        <div x-data="{
            showPass: false,
            showConfirm: false,
            password: '',
            confirmPassword: '',
            get strength() {
                let s = 0;
                if (this.password.length >= 8) s++;
                if (/[A-Z]/.test(this.password)) s++;
                if (/[0-9]/.test(this.password)) s++;
                if (/[^A-Za-z0-9]/.test(this.password)) s++;
                return s;
            },
            get strengthLabel() { return ['', 'Weak', 'Fair', 'Good', 'Strong'][this.strength] },
            get strengthColor() { return ['bg-gray-700', 'bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-emerald-500'][this.strength] },
            get strengthTextColor() { return ['text-gray-500', 'text-red-400', 'text-yellow-400', 'text-blue-400', 'text-emerald-400'][this.strength] },
            get matchPercent() {
                if (!this.confirmPassword.length || !this.password.length) return 0;
                let matches = 0;
                const len = Math.max(this.password.length, this.confirmPassword.length);
                for (let i = 0; i < len; i++) {
                    if (this.password[i] && this.confirmPassword[i] && this.password[i] === this.confirmPassword[i]) matches++;
                }
                return Math.round((matches / len) * 100);
            },
            get matchColor() {
                if (this.matchPercent === 100) return 'bg-emerald-500';
                if (this.matchPercent >= 70) return 'bg-blue-500';
                if (this.matchPercent >= 40) return 'bg-yellow-500';
                return 'bg-red-500';
            },
            get matchTextColor() {
                if (this.matchPercent === 100) return 'text-emerald-400';
                if (this.matchPercent >= 70) return 'text-blue-400';
                if (this.matchPercent >= 40) return 'text-yellow-400';
                return 'text-red-400';
            },
            get matchLabel() {
                if (this.matchPercent === 100) return 'Passwords match!';
                return this.matchPercent + '% match';
            }
        }" class="space-y-5">

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Password') }}</label>
                <div class="relative">
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••" x-model="password"
                        class="block w-full py-3 px-4 pr-12 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-200 transition-colors">
                        <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                {{-- Strength Meter --}}
                <div x-show="password.length > 0" x-transition class="mt-3">
                    <div class="flex space-x-1.5 mb-1.5">
                        <template x-for="i in 4" :key="i">
                            <div class="h-1.5 flex-1 rounded-full transition-colors duration-300" :class="i <= strength ? strengthColor : 'bg-gray-700'"></div>
                        </template>
                    </div>
                    <p class="text-xs font-bold transition-colors" :class="strengthTextColor" x-text="strengthLabel"></p>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Confirm Password') }}</label>
                <div class="relative">
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" x-model="confirmPassword"
                        class="block w-full py-3 px-4 pr-12 bg-gray-50 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-200 transition-colors">
                        <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                {{-- Match Percentage --}}
                <div x-show="confirmPassword.length > 0" x-transition class="mt-3">
                    <div class="w-full bg-gray-700 rounded-full h-1.5 mb-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full transition-all duration-500" :class="matchColor" :style="'width: ' + matchPercent + '%'"></div>
                    </div>
                    <p class="text-xs font-bold transition-colors" :class="matchTextColor" x-text="matchLabel"></p>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <input type="hidden" name="role" value="Viewer">

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/25 text-base font-bold text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-[#0B0F19] transition-all">
                {{ __('Create Account') }}
            </button>
        </div>

        <p class="text-center text-sm font-medium text-gray-600 dark:text-gray-400 mt-4">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-500 dark:text-blue-400 transition-colors">Sign in</a>
        </p>
    </form>
@endsection
