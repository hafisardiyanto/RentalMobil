@extends('layouts.admin')

@section('content')
    <div class="box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="margin: 0; color: #1e293b;">Manajemen Pegawai (Admin)</h2>
            <a href="{{ route('owner.admins.create') }}" class="btn btn-primary">+ Tambah Admin Baru</a>
        </div>

        @if(session('success'))
            <div style="background: #10B981; color: white; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <table
            style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0; text-align: left;">
                    <th style="padding: 1rem; color: #475569;">Nama Lengkap</th>
                    <th style="padding: 1rem; color: #475569;">Email</th>
                    <th style="padding: 1rem; color: #475569;">Terdaftar Sejak</th>
                    <th style="padding: 1rem; color: #475569; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr style="border-bottom: 1px solid #E2E8F0;">
                        <td style="padding: 1rem; font-weight: bold;">{{ $admin->name }}</td>
                        <td style="padding: 1rem; color: #64748B;">{{ $admin->email }}</td>
                        <td style="padding: 1rem; color: #64748B;">{{ $admin->created_at->format('d M Y') }}</td>
                        <td style="padding: 1rem; text-align: right;">
                            <a href="{{ route('owner.admins.edit', $admin->id) }}"
                                style="color: #3B82F6; text-decoration: none; font-weight: 500; margin-right: 1rem;">Edit</a>
                            <form action="{{ route('owner.admins.destroy', $admin->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Apakah Anda yakin ingin memberhentikan admin ini? Akses mereka akan dicabut.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background:none; border:none; color: #EF4444; font-weight: 500; cursor: pointer;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #64748B;">Belum ada akun Admin
                            terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection