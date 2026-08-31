@extends('layouts.admin')

@push('admin_styles')
    <link rel="stylesheet" href="{{ asset('css/admin/cars.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title m-0">Edit Armada</h1>
            <p>Perbarui informasi mobil: {{ $car->name }}.</p>
        </div>
    </div>

    <div class="box form-container">
        <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid-2">
                <div>
                    <label class="form-label">Nama Tipe Mobil</label>
                    <input type="text" name="name" value="{{ old('name', $car->name) }}" required
                        placeholder="Cth: Avanza Veloz" class="form-input">
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="form-label">Merek (Brand)</label>
                    <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" required
                        placeholder="Cth: Toyota" class="form-input">
                    @error('brand') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid-4">
                <div>
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" name="license_plate" value="{{ old('license_plate', $car->license_plate) }}" required
                        placeholder="B 1234 CD" class="form-input">
                    @error('license_plate') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="form-label">Tahun</label>
                    <input type="number" name="year" value="{{ old('year', $car->year) }}" required min="2000"
                        placeholder="Cth: 2022" class="form-input">
                    @error('year') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}"
                        required placeholder="300000" class="form-input">
                    @error('price_per_day') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="is_available" required class="form-input">
                        <option value="1" {{ old('is_available', $car->is_available) == 1 ? 'selected' : '' }}>Tersedia
                        </option>
                        <option value="0" {{ old('is_available', $car->is_available) == 0 ? 'selected' : '' }}>Booking
                        </option>
                    </select>
                    @error('is_available') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid-4">
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" placeholder="Deskripsikan keunggulan dan kenyamanan mobil ini..."
                        class="form-input">{{ old('description', $car->description) }}</textarea>
                    @error('description') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid-4">
                <div>
                    <label class="form-label">Jumlah Kursi</label>
                    <input type="number" name="seats" value="{{ old('seats', $car->seats) }}" required min="1"
                        placeholder="5" class="form-input">
                    @error('seats') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="form-label">Kapasitas Koper</label>
                    <input type="number" name="luggage" value="{{ old('luggage', $car->luggage) }}" required min="0"
                        placeholder="2" class="form-input">
                    @error('luggage') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-grid-2" style="margin-top: 15px; margin-bottom: 25px;">
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Fasilitas / Inclusions (Ceklis yang tersedia)</label>
                    <div
                        style="display: flex; gap: 20px; flex-wrap: wrap; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        @php
                            $selectedFacilities = is_array($car->facilities) ? $car->facilities : [];
                        @endphp
                        @forelse($facilities as $facility)
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->name }}" {{ (is_array(old('facilities')) && in_array($facility->name, old('facilities'))) || in_array($facility->name, $selectedFacilities) ? 'checked' : '' }}> {{ $facility->name }}
                            </label>
                        @empty
                            <span style="font-size: 0.85rem; color:#64748b;">Belum ada master data fasilitas yang ditambahkan.
                                <a href="{{ route('facilities.index') }}" style="color: #3b82f6;">Tambah Fasilitas</a></span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="upload-section">
                <label class="form-label" style="font-weight: bold; margin-bottom:10px;">Gambar Saat Ini</label>
                <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                    @if(is_array($car->images) && count($car->images) > 0)
                        @foreach($car->images as $index => $img)
                            <div class="img-wrapper" style="position: relative;">
                                <button type="button"
                                    onclick="if(confirm('Hapus foto ini dari galeri?')) document.getElementById('delete-img-{{ $index }}').submit();"
                                    style="position: absolute; top:5px; right:5px; background:#ef4444; color:white; border:none; border-radius:50%; width:25px; height:25px; cursor:pointer; font-weight:bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 10;">&times;</button>
                                <img src="{{ $img }}" alt="Foto Mobil">
                                <span class="img-label">Gambar Tersimpan</span>
                            </div>
                        @endforeach
                    @elseif($car->image_path)
                        <div class="img-wrapper">
                            <img src="{{ $car->image_path }}" alt="Foto Mobil">
                            <span class="img-label">Gambar Tersimpan</span>
                        </div>
                    @else
                        <span class="upload-placeholder">Belum ada gambar</span>
                    @endif
                </div>

                <label class="form-label">Ganti Foto Kendaraan (Opsional, Bisa pilih > 1 foto)</label>
                <div class="upload-box">
                    <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/png,image/jpg"
                        class="upload-input">
                    <div id="uploadPlaceholder" class="upload-placeholder">
                        <span class="upload-icon">📸</span>
                        <strong>Klik / Tarik file Foto ke sini untuk mengganti</strong><br>
                        <span class="upload-subtext">Maksimal 2MB per file (JPG, PNG)</span>
                    </div>
                    <div id="previewContainer" class="preview-container"></div>
                </div>
                @error('images') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Mobil</button>
                <a href="{{ route('admin.cars.index') }}" class="btn btn-cancel">Batal</a>
            </div>

        </form>
    </div>

    <script>
        document.getElementById('imageInput').addEventListener('change', function (e) {
            const previewContainer = document.getElementById('previewContainer');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            previewContainer.innerHTML = '';

            if (this.files.length > 0) {
                uploadPlaceholder.style.display = 'none';
                previewContainer.style.display = 'flex';

                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
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

    @if(is_array($car->images))
        @foreach($car->images as $index => $img)
            <form id="delete-img-{{ $index }}" action="{{ route('admin.cars.destroy-image', $car->id) }}" method="POST"
                style="display:none;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="image_url" value="{{ $img }}">
            </form>
        @endforeach
    @endif
@endsection