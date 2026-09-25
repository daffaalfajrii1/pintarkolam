@extends('layouts.user')
@section('title', 'Notifikasi')
@section('content')
<div class="pk-page-head d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2 class="pk-page-title mb-0">Notifikasi</h2>
    <form method="POST" action="{{ route('user.notifications.readAll') }}">@csrf<button class="pk-btn pk-btn-outline pk-btn-sm" type="submit">Tandai semua dibaca</button></form>
</div>
<div class="card"><div class="card-body p-0">
@forelse($notifications as $notification)
    @php $data = $notification->data; @endphp
    <div class="p-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}">
        <div class="d-flex justify-content-between gap-3">
            <div>
                <strong>{{ $data['title'] ?? 'Notifikasi' }}</strong>
                <div class="small text-muted">{{ $data['category'] ?? 'general' }} · {{ $notification->created_at->diffForHumans() }}</div>
                <p class="mb-0 mt-1">{{ $data['message'] ?? '' }}</p>
            </div>
            <form method="POST" action="{{ route('user.notifications.read', $notification->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-primary">{{ $notification->read_at ? 'Buka' : 'Baca' }}</button>
            </form>
        </div>
    </div>
@empty
    <div class="p-4 text-center text-muted">Belum ada notifikasi.</div>
@endforelse
</div></div>
{{ $notifications->links() }}
@endsection
