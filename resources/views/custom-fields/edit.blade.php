<!-- resources/views/custom-fields/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Custom Field')
@section('page-title', 'Edit Custom Field')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('custom-fields.update', $customField) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Category Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-folder me-1"></i>Kategori Data <span class="text-danger">*</span>
                        </label>
                        <select name="category" id="categorySelect" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $key => $info)
                            <option value="{{ $key }}" {{ old('category', $customField->category) == $key ? 'selected' : '' }}>
                                {{ $info['label'] }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Field akan otomatis muncul di section kategori yang dipilih</small>
                        @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="form-label">Field Name <span class="text-danger">*</span></label>
                        <input type="text" name="field_name" class="form-control @error('field_name') is-invalid @enderror" value="{{ old('field_name', $customField->field_name) }}" required>
                        <small class="text-muted">Contoh: nomor_bpjs, golongan_darah (gunakan underscore, tanpa spasi)</small>
                        @error('field_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Field Label <span class="text-danger">*</span></label>
                        <input type="text" name="field_label" class="form-control @error('field_label') is-invalid @enderror" value="{{ old('field_label', $customField->field_label) }}" required>
                        <small class="text-muted">Label yang akan ditampilkan di form</small>
                        @error('field_label')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Field Type <span class="text-danger">*</span></label>
                        <select name="field_type" id="fieldType" class="form-select @error('field_type') is-invalid @enderror" required>
                            <option value="">Pilih tipe field...</option>
                            <option value="text" {{ old('field_type', $customField->field_type) == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="textarea" {{ old('field_type', $customField->field_type) == 'textarea' ? 'selected' : '' }}>Textarea</option>
                            <option value="number" {{ old('field_type', $customField->field_type) == 'number' ? 'selected' : '' }}>Number</option>
                            <option value="date" {{ old('field_type', $customField->field_type) == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="file" {{ old('field_type', $customField->field_type) == 'file' ? 'selected' : '' }}>File</option>
                            <option value="select" {{ old('field_type', $customField->field_type) == 'select' ? 'selected' : '' }}>Select</option>
                        </select>
                        @error('field_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="optionsField" style="display: {{ old('field_type', $customField->field_type) == 'select' ? 'block' : 'none' }};">
                        <label class="form-label">Field Options</label>
                        <input type="text" name="field_options" class="form-control @error('field_options') is-invalid @enderror" value="{{ old('field_options', $customField->field_options) }}" placeholder="Contoh: A,B,AB,O">
                        <small class="text-muted">Hanya untuk tipe Select. Pisahkan dengan koma.</small>
                        @error('field_options')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Order (Urutan tampil dalam kategori)</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $customField->order) }}" min="0">
                        <small class="text-muted">Semakin kecil angka, semakin atas posisinya</small>
                        @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_required" class="form-check-input" id="isRequired" value="1" {{ old('is_required', $customField->is_required) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isRequired">
                            Field ini wajib diisi (Required)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update
                        </button>
                        <a href="{{ route('custom-fields.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Current Info -->
        <div class="card border-info mb-3">
            <div class="card-header bg-info bg-opacity-10">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Info Saat Ini
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">Kategori:</small>
                    <div class="fw-bold">{{ $customField->getCategoryInfo()['label'] }}</div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Type:</small>
                    <div class="fw-bold">{{ ucfirst($customField->field_type) }}</div>
                </div>
                <div>
                    <small class="text-muted">Status:</small>
                    <div>
                        @if($customField->is_required)
                        <span class="badge bg-danger">Required</span>
                        @else
                        <span class="badge bg-secondary">Optional</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <h6 class="mb-0">
                    <i class="bi bi-eye me-2"></i>Preview
                </h6>
            </div>
            <div class="card-body">
                <div id="categoryPreview" class="alert alert-secondary mb-3">
                    <small class="text-muted">Field akan muncul di:</small>
                    <div class="fw-bold mt-1">{{ $customField->getCategoryInfo()['label'] }}</div>
                </div>
                <p class="text-muted small mb-0">Perubahan kategori akan memindahkan field ke section yang baru di form karyawan.</p>
            </div>
        </div>

        <!-- Warning -->
        <div class="alert alert-warning mt-3">
            <small>
                <i class="bi bi-exclamation-triangle me-1"></i>
                <strong>Perhatian:</strong> Data yang sudah diisi tidak akan hilang saat memindah kategori.
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show/hide options field
document.getElementById('fieldType').addEventListener('change', function() {
    const optionsField = document.getElementById('optionsField');
    if (this.value === 'select') {
        optionsField.style.display = 'block';
    } else {
        optionsField.style.display = 'none';
    }
});

// Category preview
document.getElementById('categorySelect').addEventListener('change', function() {
    const preview = document.getElementById('categoryPreview');
    const categories = {
        'data_pribadi': { label: '1️⃣ Data Pribadi', color: 'primary' },
        'data_kontak': { label: '2️⃣ Data Kontak', color: 'success' },
        'data_pendidikan': { label: '3️⃣ Data Pendidikan', color: 'info' },
        'data_kepegawaian': { label: '4️⃣ Data Kepegawaian', color: 'warning' },
        'lainnya': { label: '➕ Data Lainnya', color: 'secondary' }
    };
    
    if (this.value && categories[this.value]) {
        const cat = categories[this.value];
        preview.className = `alert alert-${cat.color}`;
        preview.querySelector('div').innerHTML = `<i class="bi bi-check-circle me-1"></i>${cat.label}`;
    }
});

// Trigger on load
if (document.getElementById('categorySelect').value) {
    document.getElementById('categorySelect').dispatchEvent(new Event('change'));
}
</script>
@endpush