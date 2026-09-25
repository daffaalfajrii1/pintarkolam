@extends('layouts.admin')
@section('title', 'Pengaturan Website')
@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="row g-4">
@csrf @method('PUT')

<div class="col-lg-6">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Logo & Branding</h5>
        <div class="mb-3">
            <label class="form-label">Nama Situs</label>
            <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $site_branding['site_name'] ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tagline</label>
            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $site_branding['tagline'] ?? '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/*">
            @if(!empty($site_branding['logo_path']))
                <img src="{{ asset('storage/'.$site_branding['logo_path']) }}" alt="logo" class="mt-2" style="max-height:48px">
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">Favicon / Icon Bar</label>
            <input type="file" name="favicon" class="form-control" accept="image/*">
        </div>
    </div></div>
</div>

<div class="col-lg-6">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Header</h5>
        @foreach(['hours'=>'Jam Operasional','location'=>'Lokasi','phone'=>'Kontak','facebook'=>'Facebook','instagram'=>'Instagram','youtube'=>'YouTube'] as $name => $label)
        <div class="mb-3">
            <label class="form-label">{{ $label }}</label>
            <input type="text" name="{{ $name }}" class="form-control" value="{{ old($name, $site_header[$name] ?? '') }}">
        </div>
        @endforeach
    </div></div>
</div>

<div class="col-lg-6">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Footer</h5>
        <div class="mb-3">
            <label class="form-label">Tentang singkat</label>
            <textarea name="about" class="form-control" rows="3">{{ old('about', $site_footer['about'] ?? '') }}</textarea>
        </div>
        <div class="mb-3"><label class="form-label">Email</label><input type="text" name="email" class="form-control" value="{{ old('email', $site_footer['email'] ?? '') }}"></div>
        <div class="mb-3"><label class="form-label">Domain</label><input type="text" name="domain" class="form-control" value="{{ old('domain', $site_footer['domain'] ?? '') }}"></div>
        <div class="mb-3"><label class="form-label">Copyright</label><input type="text" name="copyright" class="form-control" value="{{ old('copyright', $site_footer['copyright'] ?? '') }}"></div>
    </div></div>
</div>

<div class="col-lg-6">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Hero Landing</h5>
        <div class="mb-3"><label class="form-label">Judul</label><input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $landing_hero['title'] ?? '') }}"></div>
        <div class="mb-3"><label class="form-label">Tagline</label><input type="text" name="hero_tagline" class="form-control" value="{{ old('hero_tagline', $landing_hero['tagline'] ?? '') }}"></div>
        <div class="mb-3"><label class="form-label">Subtitle</label><input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $landing_hero['subtitle'] ?? '') }}"></div>
    </div></div>
</div>

<div class="col-12">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Carousel (maks 3 slide)</h5>
        @for($i=0;$i<3;$i++)
            @php $slide = $landing_carousel['slides'][$i] ?? ['title'=>'','text'=>'','image'=>null]; @endphp
            <div class="border rounded p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-4"><input type="text" name="carousel_title[{{ $i }}]" class="form-control" placeholder="Judul slide" value="{{ $slide['title'] }}"></div>
                    <div class="col-md-5"><input type="text" name="carousel_text[{{ $i }}]" class="form-control" placeholder="Teks" value="{{ $slide['text'] }}"></div>
                    <div class="col-md-3"><input type="file" name="carousel_image[{{ $i }}]" class="form-control" accept="image/*"></div>
                </div>
                @if(!empty($slide['image']))<img src="{{ asset('storage/'.$slide['image']) }}" class="mt-2" style="max-height:60px">@endif
            </div>
        @endfor
    </div></div>
</div>

<div class="col-12">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Foto Section "Kenapa PintarKolam"</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Foto kiri (rekomendasi ~650×750)</label>
                <input type="file" name="why_choose_image" class="form-control" accept="image/*">
                @php $whyImg = $landing_sections['why_choose_image'] ?? null; @endphp
                @if($whyImg)
                    <img src="{{ asset('storage/'.$whyImg) }}" class="mt-2 img-fluid rounded" style="max-height:160px" alt="why choose">
                @else
                    <img src="{{ asset('viscous/assets/img/why-choose/1.png') }}" class="mt-2 img-fluid rounded" style="max-height:160px" alt="default">
                    <div class="small text-muted mt-1">Belum diubah — memakai foto default template.</div>
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Background section (opsional)</label>
                <input type="file" name="why_choose_bg" class="form-control" accept="image/*">
                @php $whyBg = $landing_sections['why_choose_bg'] ?? null; @endphp
                @if($whyBg)
                    <img src="{{ asset('storage/'.$whyBg) }}" class="mt-2 img-fluid rounded" style="max-height:160px" alt="bg">
                @endif
            </div>
        </div>
    </div></div>
</div>

<div class="col-12">
    <div class="card"><div class="card-body">
        <h5 class="mb-3">Banner</h5>
        @for($i=0;$i<2;$i++)
            @php $banner = $landing_banners['items'][$i] ?? ['title'=>'','link'=>'','image'=>null]; @endphp
            <div class="border rounded p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-4"><input type="text" name="banner_title[{{ $i }}]" class="form-control" placeholder="Judul banner" value="{{ $banner['title'] }}"></div>
                    <div class="col-md-4"><input type="text" name="banner_link[{{ $i }}]" class="form-control" placeholder="Link" value="{{ $banner['link'] }}"></div>
                    <div class="col-md-4"><input type="file" name="banner_image[{{ $i }}]" class="form-control" accept="image/*"></div>
                </div>
            </div>
        @endfor
        <button class="btn btn-primary">Simpan Pengaturan</button>
    </div></div>
</div>
</form>
@endsection
