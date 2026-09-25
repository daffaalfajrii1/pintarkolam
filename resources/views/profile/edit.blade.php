@extends('layouts.user')
@section('title', 'Profil')
@section('content')
<div class="row g-3">
    <div class="col-lg-6" id="edit-profil">
        <div class="card"><div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3"><div class="card-body">
            @include('profile.partials.update-password-form')
        </div></div>
        <div class="card"><div class="card-body">
            @include('profile.partials.delete-user-form')
        </div></div>
    </div>
</div>
@endsection
