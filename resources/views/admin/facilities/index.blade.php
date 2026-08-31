@extends('layouts.admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title m-0">Master Data Fasilitas Mobil</h1>
            <p>Kelola daftar fasilitas / keunggulan yang bisa di-checklist saat membuat mobil baru.</p>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
        <!-- Form Tambah -->
        <div class="box">
            <h2 style="font-size: 1.1rem; margin-top:0;">Tambah Fasilitas Baru</h2>
            <form action="{{ route('facilities.store') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label class="form-label">Nama Fasilitas</label>
                    <input type="text" name="name" required class="form-input" placeholder="Contoh: Lepas Kunci">
                    @error('name') <span style="color:red; font-size:0.8rem;">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Tambah Fasilitas</button>
            </form>
        </div>

        <!-- Tabel Fasilitas -->
        <div class="box">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Fasilitas</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $index => $facility)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="font-weight: 500;">{{ $facility->name }}</td>
                            <td>
                                <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">Belum ada master data fasilitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection