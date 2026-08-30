@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/owner/roles.css') }}">
@endpush

@section('content')
    <div class="box">
        <div class="role-header-wrapper">
            <h2>Manajemen Jabatan & Hak Akses</h2>
            <a href="{{ route('owner.roles.create') }}" class="btn btn-primary">+ Tambah Jabatan</a>
        </div>

        @if(session('success'))
            <div class="role-alerts success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="role-alerts error">
                {{ session('error') }}
            </div>
        @endif

        <table class="roles-table">
            <thead>
                <tr>
                    <th>Jabatan (Role)</th>
                    <th>Hak Akses Terdaftar</th>
                    <th style="text-align: center;">Jumlah Admin</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td class="role-name">{{ $role->name }}</td>
                        <td>
                            @if(in_array('view_cars', $role->permissions ?? []) || in_array('create_cars', $role->permissions ?? []) || in_array('edit_cars', $role->permissions ?? []) || in_array('delete_cars', $role->permissions ?? []))
                                <span class="perm-tag cars">🚗 Mobil</span>
                            @endif
                            @if(in_array('view_bookings', $role->permissions ?? []) || in_array('edit_bookings', $role->permissions ?? []) || in_array('manage_fines', $role->permissions ?? []) || in_array('delete_bookings', $role->permissions ?? []))
                                <span class="perm-tag bookings">📂 Booking</span>
                            @endif
                            @if(in_array('view_reports', $role->permissions ?? []))
                                <span class="perm-tag reports">📈 Laporan</span>
                            @endif
                        </td>
                        <td style="text-align: center; color: #64748B;">
                            {{ $role->users()->count() }} Pegawai
                        </td>
                        <td style="text-align: right;">
                            <a href="{{ route('owner.roles.edit', $role->id) }}"
                                class="action-edit">Edit Akses</a>
                            <form action="{{ route('owner.roles.destroy', $role->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Hapus Jabatan ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-delete-btn">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #64748B;">Belum ada jabatan khusus yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection