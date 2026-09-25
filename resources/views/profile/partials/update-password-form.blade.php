<section id="ubah-password">
    <h5 class="mb-3">Ubah Password</h5>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        <div class="mb-3">
            <label class="form-label" for="current_password">Password saat ini</label>
            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password baru</label>
            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
        </div>
        <button class="btn btn-primary">Update Password</button>
    </form>
</section>
