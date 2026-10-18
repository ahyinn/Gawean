@csrf
<div class="mb-3">
  <label class="form-label" for="title">Judul</label>
  <input type="text" id="title" name="title" value="{{ old('title', $service->title ?? '') }}" class="form-control @error('title') is-invalid @enderror">
  @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label" for="category_id">Kategori</label>
  <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
    <option value="">-- Pilih --</option>
    @foreach($categories as $category)
      <option value="{{ $category->id }}" @selected(old('category_id', $service->category_id ?? '') == $category->id)>{{ $category->name }}</option>
    @endforeach
  </select>
  @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label" for="price">Harga (Rp)</label>
  <input type="number" id="price" name="price" value="{{ old('price', $service->price ?? '') }}" class="form-control @error('price') is-invalid @enderror">
  @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label" for="description">Deskripsi</label>
  <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description ?? '') }}</textarea>
  @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label" for="image">Gambar (maks 2 MB)</label>
  <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
  @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="g-form-actions">
  <button class="btn btn-primary px-4">Simpan</button>
  <a href="{{ route('services.mine') }}" class="btn btn-outline-primary">Batal</a>
</div>
