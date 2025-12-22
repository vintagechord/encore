<x-guest-layout>
    <div class="auth-head">
        <h1 style="margin:0 0 12px; font-size:22px; letter-spacing:-0.01em">로그인</h1>
        <div class="muted" style="margin-bottom:10px">
            Encore 계정으로 로그인하거나 <a class="auth-link" href="{{ route('register') }}">회원가입</a>을 진행하세요.
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-600 bg-slate-900 shadow-sm focus:ring-offset-slate-900" name="remember">
                <span class="ms-2 text-sm text-slate-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="auth-foot">
            <div class="auth-links">
                <a class="auth-link underline text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900" href="{{ route('register') }}">
                    아직 회원이 아니신가요?
                </a>
                @if (Route::has('password.request'))
                    <a class="auth-link underline text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <x-primary-button class="primary">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
