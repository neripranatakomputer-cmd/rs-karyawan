<!-- resources/views/custom-fields/create.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Custom Field')
@section('page-title', 'Tambah Custom Field Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('custom-fields.store') }}" method="POST">
                    @csrf

                    <!-- Category Selection (BARU!) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-folder me-1"></i>Kategori Data <span class="text-danger">*</span>
                        </label>
                        <select name="category" id="categorySelect" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $key => $info)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                        <input type="text" name="field_name" class="form-control @error('field_name') is-invalid @enderror" value="{{ old('field_name') }}" required>
                        <small class="text-muted">Contoh: nomor_bpjs, golongan_darah (gunakan underscore, tanpa spasi)</small>
                        @error('field_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Field Label <span class="text-danger">*</span></label>
                        <input type="text" name="field_label" class="form-control @error('field_label') is-invalid @enderror" value="{{ old('field_label') }}" required>
                        <small class="text-muted">Label yang akan ditampilkan di form. Contoh: Nomor BPJS, Golongan Darah</small>
                        @error('field_label')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Field Type <span class="text-danger">*</span></label>
                        <select name="field_type" id="fieldType" class="form-select @error('field_type') is-invalid @enderror" required>
                            <option value="">Pilih tipe field...</option>
                            <option value="text" {{ old('field_type') == 'text' ? 'selected' : '' }}>
                                <i class="bi bi-input-cursor-text"></i> Text (Input teks biasa)
                            </option>
                            <option value="textarea" {{ old('field_type') == 'textarea' ? 'selected' : '' }}>
                                <i class="bi bi-textarea"></i> Textarea (Teks panjang)
                            </option>
                            <option value="number" {{ old('field_type') == 'number' ? 'selected' : '' }}>
                                <i class="bi bi-123"></i> Number (Angka)
                            </option>
                            <option value="date" {{ old('field_type') == 'date' ? 'selected' : '' }}>
                                <i class="bi bi-calendar-date"></i> Date (Tanggal)
                            </option>
                            <option value="file" {{ old('field_type') == 'file' ? 'selected' : '' }}>
                                <i class="bi bi-file-earmark"></i> File (Upload file)
                            </option>
                            <option value="select" {{ old('field_type') == 'select' ? 'selected' : '' }}>
                                <i class="bi bi-ui-checks"></i> Select (Dropdown pilihan)
                            </option>
                        </select>
                        @error('field_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="optionsField" style="display: none;">
                        <label class="form-label">Field Options</label>
                        <input type="text" name="field_options" class="form-control @error('field_options') is-invalid @enderror" value="{{ old('field_options') }}" placeholder="Pisahkan dengan koma. Contoh: A,B,AB,O">
                        <small class="text-muted">Hanya untuk tipe Select. Pisahkan setiap pilihan dengan koma.</small>
                        @error('field_options')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Order (Urutan tampil dalam kategori)</label>
                        <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" min="0">
                        <small class="text-muted">Semakin kecil angka, semakin atas posisinya di dalam kategori</small>
                        @error('order')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_required" class="form-check-input" id="isRequired" value="1" {{ old('is_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isRequired">
                            Field ini wajib diisi (Required)
                        </label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan
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
        <!-- Preview Card -->
        <div class="card border-info">
            <div class="card-header bg-info bg-opacity-10">
                <h6 class="mb-0">
                    <i class="bi bi-eye me-2"></i>Preview
                </h6>
            </div>
            <div class="card-body">
                <div id="categoryPreview" class="alert alert-secondary mb-3" style="display: none;">
                    <small class="text-muted">Field akan muncul di:</small>
                    <div class="fw-bold mt-1"></div>
                </div>
                <p class="text-muted small mb-0">Field akan ditampilkan di form karyawan sesuai kategori yang dipilih dengan urutan sesuai nilai Order.</p>
            </div>
        </div>

        <!-- Example Card -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Contoh Penggunaan
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Data Pribadi:</strong>
                    <small class="text-muted d-block">Golongan Darah, Status Pernikahan, Agama</small>
                </div>
                <div class="mb-3">
                    <strong>Data Kontak:</strong>
                    <small class="text-muted d-block">No Telepon Darurat, WhatsApp, Alamat Domisili</small>
                </div>
                <div class="mb-3">
                    <strong>Data Pendidikan:</strong>
                    <small class="text-muted d-block">Universitas, Jurusan, IPK, Sertifikat</small>
                </div>
                <div class="mb-3">
                    <strong>Data Kepegawaian:</strong>
                    <small class="text-muted d-block">Nomor SK, Tanggal Mulai Kerja, Status Pegawai</small>
                </div>
                <div>
                    <strong>Data Lainnya:</strong>
                    <small class="text-muted d-block">Nomor BPJS, Nomor Rekening, Hobi</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show/hide options field based on field type
document.getElementById('fieldType').addEventListener('change', function() {
    const optionsField = document.getElementById('optionsField');
    if (this.value === 'select') {
        optionsField.style.display = 'block';
    } else {
        optionsField.style.display = 'none';
    }
});

// Trigger on page load for old input
document.addEventListener('DOMContentLoaded', function() {
    const fieldType = document.getElementById('fieldType');
    if (fieldType.value === 'select') {
        document.getElementById('optionsField').style.display = 'block';
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
        preview.style.display = 'block';
        preview.className = `alert alert-${cat.color}`;
        preview.querySelector('div').innerHTML = `<i class="bi bi-check-circle me-1"></i>${cat.label}`;
    } else {
        preview.style.display = 'none';
    }
});

// Trigger on load if old value exists
if (document.getElementById('categorySelect').value) {
    document.getElementById('categorySelect').dispatchEvent(new Event('change'));
}
</script>
@endpush