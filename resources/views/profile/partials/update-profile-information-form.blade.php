<section>
    <h5 class="mb-3">Informasi Profil</h5>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <div class="mb-3">
            <label class="form-label" for="name">Nama</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
        @if (session('status') === 'profile-updated')
            <span class="text-success ms-2">Tersimpan.</span>
        @endif
    </form>
</section>
