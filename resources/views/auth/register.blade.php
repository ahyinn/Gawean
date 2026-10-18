@extends('layouts.app')
@section('title', 'Daftar')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card"><div class="card-body">
      <h4 class="mb-3">Daftar Akun</h4>
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label d-block">Saya ingin menjadi:</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="client" id="role-client"
                   @checked(in_array('client', old('roles', ['client'])))>
            <label class="form-check-label" for="role-client">Client (pencari jasa)</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="freelancer" id="role-freelancer"
                   @checked(in_array('freelancer', old('roles', [])))>
            <label class="form-check-label" for="role-freelancer">Freelancer (penyedia jasa)</label>
          </div>
          @error('roles')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-primary w-100">Daftar</button>
      </form>
    </div></div>
  </div>
</div>
@endsection