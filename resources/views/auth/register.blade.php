<x-guest-layout>
    <h2 class="fs-4 fw-bold mb-1">Daftar</h2>
    <p class="text-muted small mb-4">Buat akun pembudidaya atau buyer.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" for="name">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="phone">Telepon / WhatsApp</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="role">Daftar sebagai</label>
            <select id="role" name="role" class="form-select" required>
                <option value="pembudidaya" @selected(old('role') === 'pembudidaya')>Pembudidaya</option>
                <option value="buyer" @selected(old('role') === 'buyer')>Buyer</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mb-4">
            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">Daftar</button>
    </form>

    <div class="text-center mt-4 small pk-auth-links">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
    </div>
</x-guest-layout>
