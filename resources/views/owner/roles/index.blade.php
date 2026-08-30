@extends('layouts.admin')

@section('content')
    <div class="box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="margin: 0; color: #1e293b;">Manajemen Jabatan & Hak Akses</h2>
            <a href="{{ route('owner.roles.create') }}" class="btn btn-primary">+ Tambah Jabatan</a>
        </div>

        @if(session('success'))
            <div style="background: #10B981; color: white; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #EF4444; color: white; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        <table
            style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0; text-align: left;">
                    <th style="padding: 1rem; color: #475569;">Jabatan (Role)</th>
                    <th style="padding: 1rem; color: #475569;">Hak Akses Terdaftar</th>
                    <th style="padding: 1rem; color: #475569; text-align: center;">Jumlah Admin</th>
                    <th style="padding: 1rem; color: #475569; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roles as $role)
                    <tr style="border-bottom: 1px solid #E2E8F0;">
                        <td style="padding: 1rem; font-weight: bold;">{{ $role->name }}</td>
                        <td style="padding: 1rem;">
                            @if(in_array('manage_cars', $role->permissions ?? []))
                                <span
                                    style="display:inline-block; margin: 2px; padding: 3px 8px; background: #E0E7FF; color: #4338CA; border-radius: 12px; font-size: 0.8rem;">🚗
                                    Mobil</span>
                            @endif
                            @if(in_array('manage_bookings', $role->permissions ?? []))
                                <span
                                    style="display:inline-block; margin: 2px; padding: 3px 8px; background: #FEF3C7; color: #B45309; border-radius: 12px; font-size: 0.8rem;">📂
                                    Booking</span>
                            @endif
                            @if(in_array('view_reports', $role->permissions ?? []))
                                <span
                                    style="display:inline-block; margin: 2px; padding: 3px 8px; background: #DCFCE7; color: #15803D; border-radius: 12px; font-size: 0.8rem;">📈
                                    Laporan</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center; color: #64748B;">
                            {{ $role->users()->count() }} Pegawai
                        </td>
                        <td style="padding: 1rem; text-align: right;">
                            <a href="{{ route('owner.roles.edit', $role->id) }}"
                                style="color: #3B82F6; text-decoration: none; font-weight: 500; margin-right: 1rem;">Edit
                                Akses</a>
                            <form action="{{ route('owner.roles.destroy', $role->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Hapus Jabatan ini secara permanen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    style="background:none; border:none; color: #EF4444; font-weight: 500; cursor: pointer;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem; text-align: center; color: #64748B;">Belum ada jabatan khusus yang
                            dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection