<!-- resources/views/karyawan/import.blade.php -->
@extends('layouts.app')

@section('title', 'Import Data Karyawan')
@section('page-title', 'Import Data Karyawan dari Excel')

@section('content')
<style>
    .drop-zone {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px dashed #667eea;
        background: #f8f9ff;
        transition: all 0.3s;
        border-radius: 8px;
    }
    
    .drop-zone:hover {
        border-color: #764ba2;
        background: #f0f2ff;
    }
    
    .drop-zone.dragover {
        border-color: #10b981;
        background: #d1fae5;
    }
    
    .import-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<div class="row">
    <!-- Left Column - Upload Form -->
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-cloud-upload me-2"></i>Upload File Excel
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    
                    <div class="drop-zone mb-4" onclick="document.getElementById('fileInput').click()">
                        <div class="text-center">
                            <i class="bi bi-file-earmark-excel text-success fs-1 mb-3 d-block"></i>
                            <h5 id="dropText">Klik atau Drop File Excel</h5>
                            <p class="text-muted mb-2">Format: .xlsx, .xls, .csv</p>
                            <p class="text-muted small">Maximum size: 5MB</p>
                            <input type="file" 
                                   id="fileInput" 
                                   name="file" 
                                   class="d-none" 
                                   accept=".xlsx,.xls,.csv"
                                   required
                                   onchange="updateFileName(this)">
                            <div id="fileName" class="mt-3 fw-bold text-primary"></div>
                        </div>
                    </div>

                    @error('file')
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
                    </div>
                    @enderror

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg import-btn" id="importBtn" disabled>
                            <i class="bi bi-upload me-2"></i>Import Data
                        </button>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Download Section -->
        <div class="mt-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="bi bi-download me-2"></i>Download
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('karyawan.template') }}" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel me-2"></i>Download Template Excel
                        </a>
                        <a href="{{ route('karyawan.export') }}" class="btn btn-info">
                            <i class="bi bi-file-earmark-arrow-down me-2"></i>Export Data Existing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Instructions -->
    <div class="col-md-6">
        <!-- Instructions -->
        <div class="card border-info mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Panduan Import
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="badge bg-primary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        1
                    </div>
                    <div>
                        <h6 class="mb-1">Download Template</h6>
                        <p class="text-muted small mb-0">
                            Klik tombol "Download Template Excel" di bawah. Template sudah berisi contoh data.
                        </p>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="badge bg-success rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        2
                    </div>
                    <div>
                        <h6 class="mb-1">Isi Data</h6>
                        <p class="text-muted small mb-0">
                            Buka template di Excel. Header (baris 1) JANGAN DIUBAH! Mulai isi data dari baris ke-2.
                        </p>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="badge bg-warning rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        3
                    </div>
                    <div>
                        <h6 class="mb-1">Upload File</h6>
                        <p class="text-muted small mb-0">
                            Klik area upload atau drag & drop file Excel Anda. Tombol "Import Data" akan aktif setelah file dipilih.
                        </p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="badge bg-info rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        4
                    </div>
                    <div>
                        <h6 class="mb-1">Import</h6>
                        <p class="text-muted small mb-0">
                            Klik "Import Data" dan tunggu proses selesai. Lihat hasil & error report (jika ada).
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="card border-warning">
            <div class="card-header bg-warning">
                <h6 class="mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Penting!
                </h6>
            </div>
            <div class="card-body">
                <ul class="small mb-0">
                    <li class="mb-2"><strong>NIK</strong> harus 16 digit angka</li>
                    <li class="mb-2"><strong>Jenis Kelamin</strong> harus <code>Laki-laki</code> atau <code>Perempuan</code> (huruf besar kecil sesuai!)</li>
                    <li class="mb-2"><strong>NIP, NIK, Email</strong> harus unique (tidak boleh duplikat)</li>
                    <li class="mb-2"><strong>Email</strong> harus format email yang valid</li>
                    <li class="mb-2"><strong>Tahun Lulus</strong> harus 4 digit (contoh: 2020)</li>
                    <li class="mb-0">Semua field wajib diisi kecuali <strong>Nama dengan Gelar</strong></li>
                </ul>
            </div>
        </div>

        <!-- Format Columns -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-table me-2"></i>Format Kolom Excel
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Kolom</th>
                                <th>Contoh</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td><code>jenis_kelamin</code></td>
                                <td><span class="badge bg-primary">Laki-laki</span> <span class="badge bg-danger">Perempuan</span></td>
                            </tr>
                            <tr>
                                <td><code>nik</code></td>
                                <td>3201234567890001</td>
                            </tr>
                            <tr>
                                <td><code>email</code></td>
                                <td>budi@rs.com</td>
                            </tr>
                            <tr>
                                <td><code>tahun_lulus</code></td>
                                <td>2020</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error Display (if any from previous import) -->
@if(session('import_errors'))
<div class="card border-danger mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="bi bi-exclamation-circle me-2"></i>Detail Error Import
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="bi bi-info-circle me-2"></i>
            Berikut adalah daftar error yang terjadi saat import. Perbaiki data dan upload ulang.
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Baris</th>
                        <th>Field</th>
                        <th>Error</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('import_errors') as $error)
                    <tr>
                        <td>{{ $error['row'] ?? '-' }}</td>
                        <td>{{ $error['attribute'] ?? '-' }}</td>
                        <td>
                            @if(isset($error['errors']))
                                {{ implode(', ', $error['errors']) }}
                            @else
                                {{ $error['error'] ?? json_encode($error) }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Update file name & enable button
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDisplay = document.getElementById('fileName');
    const importBtn = document.getElementById('importBtn');
    const dropText = document.getElementById('dropText');
    
    if (fileName) {
        fileNameDisplay.innerHTML = `
            <i class="bi bi-file-earmark-check text-success me-2"></i>
            ${fileName}
        `;
        dropText.textContent = 'File dipilih! Klik Import Data';
        importBtn.disabled = false;
        importBtn.classList.remove('import-btn');
    } else {
        fileNameDisplay.innerHTML = '';
        dropText.textContent = 'Klik atau Drop File Excel';
        importBtn.disabled = true;
        importBtn.classList.add('import-btn');
    }
}

// Drag & Drop functionality
const dropZone = document.querySelector('.drop-zone');
const fileInput = document.getElementById('fileInput');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('dragover');
}

function unhighlight(e) {
    dropZone.classList.remove('dragover');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length) {
        fileInput.files = files;
        updateFileName(fileInput);
    }
}

// Form validation before submit
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('fileInput');
    
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('Silakan pilih file Excel terlebih dahulu!');
        return false;
    }
    
    // Show loading
    const importBtn = document.getElementById('importBtn');
    importBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
    importBtn.disabled = true;
});
</script>
@endpush