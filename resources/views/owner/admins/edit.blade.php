@extends('layouts.admin')

@section('content')
    <div class="box" style="max-width: 600px; margin: 0 auto;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; color: #1e293b;">Edit Data Admin</h2>

        @if ($errors->any())
            <div style="background: #FEE2E2; color: #B91C1C; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.admins.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Ganti Kata Sandi (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti password"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru (jika diisi)"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('owner.admins.index') }}" class="btn btn-outline"
                    style="padding: 0.75rem 1.5rem; text-decoration: none; border: 1px solid #ccc; color: #475569;">Batal</a>
                <button type="submit" class="btn btn-primary"
                    style="padding: 0.75rem 1.5rem; border: none; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection