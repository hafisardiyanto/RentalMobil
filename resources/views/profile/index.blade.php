@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <div class="profile-container">
        <div class="profile-wrapper">
            <div class="profile-card">
                <h2 class="profile-title">Profil Pengguna & Identitas</h2>
                <p class="profile-subtitle">Lengkapi data diri Anda untuk keperluan syarat penyewaan mobil.</p>

                @if(session('success'))
                    <div class="alert-success">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.identity') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-grid-2">
                        <div>
                            <label class="p-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="p-input">
                        </div>
                        <div>
                            <label class="p-label">Email (Read-only)</label>
                            <input type="email" value="{{ $user->email }}" disabled class="p-input p-input-readonly">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="p-label">Nomor HP (WhatsApp)</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="p-input">
                        </div>
                        <div>
                            <label class="p-label">NIK KTP</label>
                            <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" class="p-input">
                        </div>
                    </div>

                    <div class="form-group-margin">
                        <label class="p-label">Alamat Lengkap Sesuai KTP</label>
                        <textarea name="address" rows="3" class="p-input">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="doc-upload-grid">
                        <div class="doc-upload-box">
                            <h4 class="doc-upload-title">Foto KTP Asli</h4>
                            @if($user->ktp_photo)
                                <img src="{{ $user->ktp_photo }}" class="doc-preview-img">
                            @endif
                            <input type="file" name="ktp_photo" accept="image/*" class="doc-file-input">
                        </div>
                        <div class="doc-upload-box">
                            <h4 class="doc-upload-title">Foto SIM A</h4>
                            @if($user->sim_photo)
                                <img src="{{ $user->sim_photo }}" class="doc-preview-img">
                            @endif
                            <input type="file" name="sim_photo" accept="image/*" class="doc-file-input">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert-error">
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-actions">
                        <button type="submit" class="btn-save-profile">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection