@extends('layouts.app')

@section('content')
<div class="w-2/3 m-auto">
    <div class="card">
        <h1 class="text-xl font-bold mb-6 text-center">{{ __('Login') }}</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group row">
                <label for="email" class="mb-2 inline-block">{{ __('E-Mail Address') }}</label>

                <div class="mb-6">
                    <input id="email" type="email" class="border-2 @error('email') border-red-600 @enderror w-full rounded p-2" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>

                    @error('email')
                        <span class="text-sm text-red-600" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="mb-2 inline-block">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="border-2 @error('password') border-red-600 @enderror w-full rounded p-2" name="password" autocomplete="current-password">

                    @error('password')
                        <span class="text-sm text-red-600" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </div>
        </form>
        
    </div>
</div>
@endsection
