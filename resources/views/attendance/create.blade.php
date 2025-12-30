<!-- resources/views/attendance/create.blade.php -->
@extends('layouts.app')

@section('title', 'Input Absensi')
@section('page-title', 'Input Absensi Karyawan')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                    <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
                        <option value="">Pilih Karyawan...</option>
                        @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->id }}" {{ old('karyawan_id') == $karyawan->id ? 'selected' : '' }}>
                            {{ $karyawan->nama_lengkap }} ({{ $karyawan->nip }}) - {{ $karyawan->unit }}
                        </option>
                        @endforeach
                    </select>
                    @error('karyawan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                           value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                    @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="statusSelect" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach($statusLabels as $key => $info)
                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                            {{ $info['label'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4" id="jamMasukField">
                    <label class="form-label">Jam Masuk</label>
                    <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror" 
                           value="{{ old('jam_masuk') }}">
                    @error('jam_masuk')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4" id="jamKeluarField">
                    <label class="form-label">Jam Keluar</label>
                    <input type="time" name="jam_keluar" class="form-control @error('jam_keluar') is-invalid @enderror" 
                           value="{{ old('jam_keluar') }}">
                    @error('jam_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                              rows="3" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Simpan
                </button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle jam masuk/keluar berdasarkan status
document.getElementById('statusSelect').addEventListener('change', function() {
    const jamMasukField = document.getElementById('jamMasukField');
    const jamKeluarField = document.getElementById('jamKeluarField');
    const status = this.value;
    
    if (status === 'hadir' || status === 'wfh') {
        jamMasukField.style.display = 'block';
        jamKeluarField.style.display = 'block';
    } else {
        jamMasukField.style.display = 'none';
        jamKeluarField.style.display = 'none';
    }
});

// Trigger on load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('statusSelect').dispatchEvent(new Event('change'));
});
</script>
@endpush