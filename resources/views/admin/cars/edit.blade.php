@extends('layouts.admin')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 class="page-title" style="margin: 0;">Edit Armada</h1>
    <p style="color: #64748B;">Perbarui informasi mobil: {{ $car->name }}.</p>
</div>

<div class="box" style="max-width: 800px;">
    <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Nama Tipe Mobil</label>
                <input type="text" name="name" value="{{ old('name', $car->name) }}" required placeholder="Cth: Avanza Veloz" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 1rem;">
                @error('name') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Merek (Brand)</label>
                <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" required placeholder="Cth: Toyota" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 1rem;">
                @error('brand') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Plat Nomor</label>
                <input type="text" name="license_plate" value="{{ old('license_plate', $car->license_plate) }}" required placeholder="B 1234 CD" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 1rem;">
                @error('license_plate') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Tahun</label>
                <input type="number" name="year" value="{{ old('year', $car->year) }}" required min="2000" placeholder="Cth: 2022" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 1rem;">
                @error('year') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Harga (Rp)</label>
                <input type="number" name="price_per_day" value="{{ old('price_per_day', $car->price_per_day) }}" required placeholder="300000" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 1rem;">
                @error('price_per_day') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
            <div>
                <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Status</label>
                <select name="is_available" required style="width: 100%; padding: 0.8rem 1rem; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 1rem; background: white;">
                    <option value="1" {{ old('is_available', $car->is_available) == 1 ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ old('is_available', $car->is_available) == 0 ? 'selected' : '' }}>Booking</option>
                </select>
                @error('is_available') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Gambar Saat Ini</label>
            <div style="margin-bottom: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                @if(is_array($car->images) && count($car->images) > 0)
                    @foreach($car->images as $img)
                        <div style="padding: 0.5rem; border: 1px solid #E2E8F0; border-radius: 8px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center; width: 140px;">
                            <img src="{{ $img }}" alt="Foto Mobil" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                            <span style="margin-top: 0.5rem; font-size: 0.75rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: inline-block; text-align: center;">Gambar Tersimpan</span>
                        </div>
                    @endforeach
                @elseif($car->image_path)
                    <div style="padding: 0.5rem; border: 1px solid #E2E8F0; border-radius: 8px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center; width: 140px;">
                        <img src="{{ $car->image_path }}" alt="Foto Mobil" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px;">
                        <span style="margin-top: 0.5rem; font-size: 0.75rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: inline-block; text-align: center;">Gambar Tersimpan</span>
                    </div>
                @else
                    <span style="color: #64748B;">Belum ada gambar</span>
                @endif
            </div>
            
            <label style="display: block; font-weight: 500; font-size: 0.95rem; margin-bottom: 0.5rem; color: var(--text-main);">Ganti Foto Kendaraan (Opsional, Bisa pilih > 1 foto)</label>
            <div style="border: 2px dashed #CBD5E1; padding: 2rem; border-radius: 8px; text-align: center; background: #F8FAFC; position: relative;">
                <input type="file" name="images[]" id="imageInput" multiple accept="image/jpeg,image/png,image/jpg" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer; z-index: 10;">
                <div id="uploadPlaceholder" style="color: #64748B;">
                    <span style="font-size: 2rem; display: block; margin-bottom: 0.5rem;">📸</span>
                    <strong>Klik / Tarik file Foto ke sini untuk mengganti</strong><br>
                    <span style="font-size: 0.85rem;">Maksimal 2MB per file (JPG, PNG)</span>
                </div>
                <div id="previewContainer" style="display: none; gap: 1rem; flex-wrap: wrap; justify-content: center; position: relative; z-index: 5; margin-top: 1rem;"></div>
            </div>
            @error('images') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem; border-radius: 8px; border: none; font-size: 1rem; background: var(--primary); color: white; font-weight: 600; cursor: pointer;">Update Mobil</button>
            <a href="{{ route('admin.cars.index') }}" class="btn btn-outline" style="padding: 0.8rem 2rem; border-radius: 8px; text-decoration: none; color: #64748B; background: transparent; border: 1px solid #CBD5E1; font-weight: 600;">Batal</a>
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
                    wrapper.style = 'padding: 0.5rem; border: 1px solid #E2E8F0; border-radius: 8px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center; width: 140px; z-index: 5; position: relative;';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style = 'width: 100%; height: 100px; object-fit: cover; border-radius: 4px;';
                    
                    const label = document.createElement('span');
                    label.innerText = file.name;
                    label.style = 'margin-top: 0.5rem; font-size: 0.75rem; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; display: inline-block; text-align: center;';
                    
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
