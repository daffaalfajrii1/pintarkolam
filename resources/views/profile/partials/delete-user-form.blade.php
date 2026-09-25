<section>
    <h5 class="mb-2 text-danger">Hapus Akun</h5>
    <p class="small text-muted">Setelah akun dihapus, semua data akan hilang permanen.</p>
    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin hapus akun?')">
        @csrf
        @method('delete')
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input id="password" name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-outline-danger">Hapus Akun</button>
    </form>
</section>
