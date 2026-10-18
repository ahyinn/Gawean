@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
@php
    [$jurusanLama, $kampusLama] = $user->campusParts();
    $skillsValue = old('skills', $user->skills ?? []);
    $skillsValue = is_array($skillsValue) ? implode(', ', $skillsValue) : $skillsValue;
    $skillsError = $errors->first('skills') ?: $errors->first('skills.*');
@endphp

<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Profil Saya</span>
</div>

<div class="mb-4">
    <h1 class="g-page-title">Profil Saya</h1>
    <p class="g-page-sub">Atur nama, foto, dan info kampus yang tampil di profil publikmu.</p>
</div>

<div class="row g-4">
    {{-- Ringkasan --}}
    <div class="col-lg-4">
        <div class="g-panel g-panel-pad text-center">
            <x-avatar :user="$user" :size="120" class="mx-auto mb-3" />
            <h2 class="h5 mb-1">{{ $user->name }}</h2>
            <div class="small text-muted mb-2" style="overflow-wrap:anywhere">{{ $user->email }}</div>
            <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                @if($user->is_freelancer)<span class="g-freelancer-badge"><i class="bi bi-lightning-charge-fill"></i> Freelancer</span>@endif
                @if($user->is_client)<span class="g-skill-tag">Client</span>@endif
            </div>

            <div class="d-flex flex-column gap-2">
                @if($user->is_freelancer)
                    <a href="{{ route('freelancers.show', $user) }}" class="btn btn-outline-primary btn-sm">Lihat profil publik</a>
                @endif
                @if($user->is_client)
                    <a href="{{ route('clients.show', $user) }}" class="btn btn-outline-primary btn-sm">Lihat profil client</a>
                @endif
            </div>

            @if($user->photo)
                <form method="POST" action="{{ route('profile.photo.destroy') }}" class="mt-3"
                      onsubmit="return confirm('Hapus foto profil?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-link btn-sm text-danger" style="--bs-btn-color:var(--g-bad)"><i class="bi bi-trash me-1"></i> Hapus foto</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Form --}}
    <div class="col-lg-8">
        <div class="g-panel g-panel-pad g-form-panel">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="photo">Foto profil</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp"
                           class="form-control @error('photo') is-invalid @enderror">
                    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">JPG, PNG, atau WEBP. Maksimal 2 MB. Memilih foto baru akan mengganti yang lama.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name">Nama</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="255"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" maxlength="1000"
                              class="form-control @error('bio') is-invalid @enderror"
                              placeholder="Ceritakan sedikit tentang dirimu">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="jurusan">Jurusan</label>
                        <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan', $jurusanLama) }}" maxlength="100"
                               class="form-control @error('jurusan') is-invalid @enderror" placeholder="Mis. Teknik Informatika">
                        @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="kampus">Kampus</label>
                        <input type="text" id="kampus" name="kampus" value="{{ old('kampus', $kampusLama) }}" maxlength="100"
                               class="form-control @error('kampus') is-invalid @enderror" placeholder="Mis. Universitas Maritim Raja Ali Haji">
                        @error('kampus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12"><div class="form-text mt-0">Jurusan dan kampus diisi berpasangan (keduanya, atau kosongkan keduanya).</div></div>
                </div>

                @if($user->is_freelancer)
                    <div class="mb-3">
                        <label class="form-label" for="skills">Keahlian</label>
                        <input type="text" id="skills" name="skills" value="{{ $skillsValue }}"
                               class="form-control @if($skillsError) is-invalid @endif" placeholder="Mis. Logo Design, Figma, Branding">
                        @if($skillsError)<div class="invalid-feedback">{{ $skillsError }}</div>@endif
                        <div class="form-text">Pisahkan dengan koma. Maksimal 8 keahlian, tiap keahlian maksimal 30 karakter.</div>
                    </div>
                @endif

                <div class="g-form-actions">
                    <button class="btn btn-primary px-4">Simpan Perubahan</button>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
