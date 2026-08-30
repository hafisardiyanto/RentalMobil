@extends('layouts.admin')

@section('content')
    <div class="box" style="max-width: 600px; margin: 0 auto;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; color: #1e293b;">Pendaftaran Admin Baru</h2>

        @if ($errors->any())
            <div style="background: #FEE2E2; color: #B91C1C; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.admins.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Jabatan (Role Pegawai)</label>
                <select name="admin_role_id"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Tidak Memiliki Jabatan Khusus (Hanya Dashboard) --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kata Sandi (Password)</label>
                <input type="password" name="password" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('owner.admins.index') }}" class="btn btn-outline"
                    style="padding: 0.75rem 1.5rem; text-decoration: none; border: 1px solid #ccc; color: #475569;">Batal</a>
                <button type="submit" class="btn btn-primary"
                    style="padding: 0.75rem 1.5rem; border: none; cursor: pointer;">Daftarkan Admin</button>
            </div>
        </form>
    </div>
@endsection