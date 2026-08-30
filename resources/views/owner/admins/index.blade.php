@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/owner/admins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owner/roles.css') }}">
@endpush

@section('content')
    <div class="box">
        <div class="admin-header-wrapper">
            <h2>Manajemen Pegawai (Admin)</h2>
            <a href="{{ route('owner.admins.create') }}" class="btn btn-primary">+ Tambah Admin Baru</a>
        </div>

        @if(session('success'))
            <div class="admin-alerts success">
                {{ session('success') }}
            </div>
        @endif

        <table class="admins-table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Terdaftar Sejak</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td class="admin-name">
                            {{ $admin->name }}
                            @if($admin->adminRole)
                                <br><span class="admin-role-badge">{{ $admin->adminRole->name }}</span>
                            @endif
                        </td>
                        <td style="color: #64748B;">{{ $admin->email }}</td>
                        <td style="color: #64748B;">{{ $admin->created_at->format('d M Y') }}</td>
                        <td style="text-align: right;">
                            <a href="{{ route('owner.admins.edit', $admin->id) }}" class="action-edit">Edit</a>
                            <form action="{{ route('owner.admins.destroy', $admin->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Apakah Anda yakin ingin memberhentikan admin ini? Akses mereka akan dicabut.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete-btn">Hapus</button>
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