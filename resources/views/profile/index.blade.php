@extends('layouts.app')

@section('content')
    <div style="background: var(--background); min-height: calc(100vh - 80px); padding: 3rem 5%;">
        <div style="max-width: 800px; margin: 0 auto;">

            <div style="background: white; padding: 2.5rem; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <h2 style="margin-bottom: 0.5rem; color: var(--secondary);">Profil Pengguna & Identitas</h2>
                <p style="color: #64748B; margin-bottom: 2rem;">Lengkapi data diri Anda untuk keperluan syarat penyewaan
                    mobil.</p>

                @if(session('success'))
                    <div
                        style="padding: 1rem; background: #D1FAE5; color: #065F46; border-radius: 8px; margin-bottom: 1.5rem; font-weight: bold;">
                        ✅ {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.identity') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label
                                style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary);">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 8px;">
                        </div>
                        <div>
                            <label
                                style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary);">Email
                                (Read-only)</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 8px; background: #F1F5F9;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label
                                style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary);">Nomor
                                HP (WhatsApp)</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 8px;">
                        </div>
                        <div>
                            <label
                                style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary);">NIK
                                KTP</label>
                            <input type="text" name="nik" value="{{ old('nik', $user->nik) }}"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 8px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label
                            style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: var(--secondary);">Alamat
                            Lengkap Sesuai KTP</label>
                        <textarea name="address" rows="3"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #CBD5E1; border-radius: 8px;">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                        <div
                            style="border: 2px dashed #CBD5E1; padding: 1.5rem; text-align: center; border-radius: 12px; position: relative;">
                            <h4 style="margin-bottom: 1rem;">Foto KTP Asli</h4>
                            @if($user->ktp_photo)
                                <img src="{{ $user->ktp_photo }}"
                                    style="max-height: 150px; border-radius: 8px; margin-bottom: 1rem;">
                            @endif
                            <input type="file" name="ktp_photo" accept="image/*" style="width: 100%; font-size: 0.85rem;">
                        </div>
                        <div
                            style="border: 2px dashed #CBD5E1; padding: 1.5rem; text-align: center; border-radius: 12px; position: relative;">
                            <h4 style="margin-bottom: 1rem;">Foto SIM A</h4>
                            @if($user->sim_photo)
                                <img src="{{ $user->sim_photo }}"
                                    style="max-height: 150px; border-radius: 8px; margin-bottom: 1rem;">
                            @endif
                            <input type="file" name="sim_photo" accept="image/*" style="width: 100%; font-size: 0.85rem;">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div
                            style="background: #FEE2E2; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <ul style="margin: 0; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="text-align: right;">
                        <button type="submit"
                            style="background: var(--primary); color: white; padding: 0.8rem 2rem; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection