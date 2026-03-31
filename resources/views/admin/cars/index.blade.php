@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 class="page-title" style="margin: 0;">Daftar Armada Mobil</h1>
    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary" style="padding: 0.8rem 1.5rem; text-decoration: none; color: white; background: var(--primary); border-radius: 8px;">+ Tambah Mobil Baru</a>
</div>

<div class="box" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background: #F8FAFC; border-bottom: 2px solid #E2E8F0;">
            <tr>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Foto</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Mobil & Merek</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Plat & Tahun</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Harga Sewa / Hari</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Status</th>
                <th style="padding: 1.2rem; color: #64748B; text-transform: uppercase; font-size: 0.85rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cars as $car)
            <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.3s;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                <td style="padding: 1.2rem;">
                    @if($car->image_path)
                        <img src="{{ $car->image_path }}" alt="Foto Mobil" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px;">
                    @else
                        <div style="width: 80px; height: 50px; background: #E2E8F0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94A3B8; font-size: 0.8rem;">No Img</div>
                    @endif
                </td>
                <td style="padding: 1.2rem;">
                    <div style="font-weight: 600; font-size: 1.05rem; color: var(--text-main);">{{ $car->name }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">{{ $car->brand }}</div>
                </td>
                <td style="padding: 1.2rem;">
                    <div style="font-weight: 500; font-family: monospace; background: #E2E8F0; padding: 0.2rem 0.5rem; border-radius: 4px; display: inline-block; margin-bottom: 0.3rem;">{{ $car->license_plate }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Tahun: {{ $car->year }}</div>
                </td>
                <td style="padding: 1.2rem;">
                    <span style="font-weight: 600; color: var(--primary);">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                </td>
                <td style="padding: 1.2rem;">
                    @if($car->is_available)
                        <span style="background: #D1FAE5; color: #065F46; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Tersedia</span>
                    @else
                        <span style="background: #FEE2E2; color: #991B1B; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Disewa</span>
                    @endif
                </td>
                <td style="padding: 1.2rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.cars.edit', $car->id) }}" style="color: #3B82F6; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Edit</a>
                        <span style="color: #E2E8F0;">|</span>
                        <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mobil ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #EF4444; font-weight: 600; font-size: 0.9rem; cursor: pointer; padding: 0;">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 3rem; color: #64748B;">
                    🚗 Belum ada koleksi mobil yang ditambahkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
