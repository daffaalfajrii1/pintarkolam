<x-guest-layout>
    <h2 class="fs-4 fw-bold mb-1">Masuk</h2>
    <p class="text-muted small mb-4">Masuk ke akun PintarKolam Anda.</p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label" for="remember_me">Ingat saya</label>
            </div>
            @if (Route::has('password.request'))
                <a class="small pk-auth-links" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">Masuk</button>
    </form>

    <div class="text-center mt-4 small pk-auth-links">
        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
    </div>
</x-guest-layout>
