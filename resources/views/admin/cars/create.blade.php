@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title" style="margin: 0;">Tambah Armada Baru</h1>
        <p>Lengkapi detail mobil yang akan ditambahkan ke katalog rental.</p>
    </div>
</div>

<div class="box form-container">
    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid-2">
            <div>
                <label class="form-label">Nama Tipe Mobil</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Cth: Avanza Veloz" class="form-input">
                @error('name') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Merek (Brand)</label>
                <input type="text" name="brand" value="{{ old('brand') }}" required placeholder="Cth: Toyota" class="form-input">
                @error('brand') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-grid-4">
            <div>
                <label class="form-label">Plat Nomor</label>
                <input type="text" name="license_plate" value="{{ old('license_plate') }}" required placeholder="B 1234 CD" class="form-input">
                @error('license_plate') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Tahun</label>
                <input type="number" name="year" value="{{ old('year') }}" required min="2000" placeholder="Cth: 2022" class="form-input">
                @error('year') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price_per_day" value="{{ old('price_per_day') }}" required placeholder="300000" class="form-input">
                @error('price_per_day') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="is_available" required class="form-input">
                    <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Booking</option>
                </select>
                @error('is_available') <span class="error-msg">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="upload-section">
            <label class="form-label">Unggah Foto Kendaraan (Bisa lebih dari 1)</label>
            <div class="upload-box">
                <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/png,image/jpg" required class="upload-input">
                <div id="uploadPlaceholder" class="upload-placeholder">
                    <span class="upload-icon">📸</span>
                    <strong>Klik / Tarik file Foto ke sini</strong><br>
                    <span class="upload-subtext">Bisa upload beberapa file (Maksimal 2MB per file)</span>
                </div>
                <div id="previewContainer" class="preview-container"></div>
            </div>
            @error('images') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Mobil</button>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-cancel">Batal</a>
        </div>

    </form>
</div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('previewContainer');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        previewContainer.innerHTML = '';
        
        if (this.files.length > 0) {
            uploadPlaceholder.style.display = 'none';
            previewContainer.style.display = 'flex';
            
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'img-wrapper';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    
                    const label = document.createElement('span');
                    label.innerText = file.name;
                    label.className = 'img-label';
                    
                    wrapper.appendChild(img);
                    wrapper.appendChild(label);
                    previewContainer.appendChild(wrapper);
                }
                reader.readAsDataURL(file);
            });
        } else {
            previewContainer.style.display = 'none';
            uploadPlaceholder.style.display = 'block';
        }
    });
</script>
@endsection
