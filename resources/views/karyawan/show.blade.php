<!-- resources/views/karyawan/show.blade.php -->
@extends('layouts.app')

@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Data Karyawan')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($karyawan->foto_profil)
                <img src="{{ asset('storage/' . $karyawan->foto_profil) }}" 
                     class="rounded-circle mb-3" 
                     width="150" 
                     height="150" 
                     style="object-fit: cover;"
                     alt="{{ $karyawan->nama_lengkap }}"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($karyawan->nama_lengkap) }}&size=150&background=667eea&color=fff';">
                @else
                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" 
                     style="width: 150px; height: 150px;">
                    <i class="bi bi-person fs-1 text-white"></i>
                </div>
                @endif
                <h4>{{ $karyawan->nama_lengkap }}</h4>
                @if($karyawan->nama_gelar)
                <p class="text-muted">{{ $karyawan->nama_gelar }}</p>
                @endif
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('karyawan.destroy', $karyawan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- 1️⃣ Data Pribadi -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>1️⃣ Data Pribadi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>NIP:</strong>
                        <p class="mb-0">{{ $karyawan->nip }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>NIK:</strong>
                        <p class="mb-0">{{ $karyawan->nik }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Jenis Kelamin:</strong>
                        <p class="mb-0">{{ $karyawan->jenis_kelamin }}</p>
                    </div>

                    <!-- Custom Fields - Data Pribadi -->
                    @php
                        $customFieldsDataPribadi = $customFieldValues->filter(function($cfValue) {
                            return $cfValue->customField->category === 'data_pribadi';
                        });
                    @endphp
                    @foreach($customFieldsDataPribadi as $cfValue)
                    <div class="col-md-6 mb-3">
                        <strong>{{ $cfValue->customField->field_label }}:</strong>
                        @if($cfValue->customField->field_type == 'file' && $cfValue->value)
                        <br>
                        <a href="{{ asset('storage/' . $cfValue->value) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark me-1"></i>Lihat File
                        </a>
                        @else
                        <p class="mb-0">{{ $cfValue->value ?: '-' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 2️⃣ Data Kontak -->
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-telephone me-2"></i>2️⃣ Data Kontak
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>No. HP:</strong>
                        <p class="mb-0">
                            <a href="tel:{{ $karyawan->no_hp }}" class="text-decoration-none">
                                <i class="bi bi-telephone-fill me-1"></i>{{ $karyawan->no_hp }}
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">
                            <a href="mailto:{{ $karyawan->email }}" class="text-decoration-none">
                                <i class="bi bi-envelope-fill me-1"></i>{{ $karyawan->email }}
                            </a>
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Alamat:</strong>
                        <p class="mb-0">{{ $karyawan->alamat }}</p>
                    </div>

                    <!-- Custom Fields - Data Kontak -->
                    @php
                        $customFieldsDataKontak = $customFieldValues->filter(function($cfValue) {
                            return $cfValue->customField->category === 'data_kontak';
                        });
                    @endphp
                    @foreach($customFieldsDataKontak as $cfValue)
                    <div class="col-md-6 mb-3">
                        <strong>{{ $cfValue->customField->field_label }}:</strong>
                        @if($cfValue->customField->field_type == 'file' && $cfValue->value)
                        <br>
                        <a href="{{ asset('storage/' . $cfValue->value) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark me-1"></i>Lihat File
                        </a>
                        @else
                        <p class="mb-0">{{ $cfValue->value ?: '-' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 3️⃣ Data Pendidikan -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-mortarboard me-2"></i>3️⃣ Data Pendidikan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Pendidikan Terakhir:</strong>
                        <p class="mb-0">
                            <span class="badge bg-info">{{ $karyawan->pendidikan_terakhir }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Tahun Lulus:</strong>
                        <p class="mb-0">{{ $karyawan->tahun_lulus }}</p>
                    </div>
                    @if($karyawan->ijazah)
                    <div class="col-md-6 mb-3">
                        <strong>Ijazah:</strong><br>
                        <a href="{{ asset('storage/' . $karyawan->ijazah) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Lihat Dokumen
                        </a>
                    </div>
                    @endif

                    <!-- Custom Fields - Data Pendidikan -->
                    @php
                        $customFieldsDataPendidikan = $customFieldValues->filter(function($cfValue) {
                            return $cfValue->customField->category === 'data_pendidikan';
                        });
                    @endphp
                    @foreach($customFieldsDataPendidikan as $cfValue)
                    <div class="col-md-6 mb-3">
                        <strong>{{ $cfValue->customField->field_label }}:</strong>
                        @if($cfValue->customField->field_type == 'file' && $cfValue->value)
                        <br>
                        <a href="{{ asset('storage/' . $cfValue->value) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark me-1"></i>Lihat File
                        </a>
                        @else
                        <p class="mb-0">{{ $cfValue->value ?: '-' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 4️⃣ Data Kepegawaian -->
        <div class="card mb-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="bi bi-briefcase me-2"></i>4️⃣ Data Kepegawaian
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Jabatan:</strong>
                        <p class="mb-0">
                            <span class="badge bg-primary">{{ $karyawan->jabatan }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Unit:</strong>
                        <p class="mb-0">
                            <span class="badge bg-success">{{ $karyawan->unit }}</span>
                        </p>
                    </div>
                    @if($karyawan->sip)
                    <div class="col-md-6 mb-3">
                        <strong>SIP (Surat Izin Praktik):</strong><br>
                        <a href="{{ asset('storage/' . $karyawan->sip) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Lihat Dokumen
                        </a>
                    </div>
                    @endif
                    @if($karyawan->str)
                    <div class="col-md-6 mb-3">
                        <strong>STR (Surat Tanda Registrasi):</strong><br>
                        <a href="{{ asset('storage/' . $karyawan->str) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Lihat Dokumen
                        </a>
                    </div>
                    @endif

                    <!-- Custom Fields - Data Kepegawaian -->
                    @php
                        $customFieldsDataKepegawaian = $customFieldValues->filter(function($cfValue) {
                            return $cfValue->customField->category === 'data_kepegawaian';
                        });
                    @endphp
                    @foreach($customFieldsDataKepegawaian as $cfValue)
                    <div class="col-md-6 mb-3">
                        <strong>{{ $cfValue->customField->field_label }}:</strong>
                        @if($cfValue->customField->field_type == 'file' && $cfValue->value)
                        <br>
                        <a href="{{ asset('storage/' . $cfValue->value) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark me-1"></i>Lihat File
                        </a>
                        @else
                        <p class="mb-0">{{ $cfValue->value ?: '-' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ➕ Data Lainnya (jika ada) -->
        @php
            $customFieldsLainnya = $customFieldValues->filter(function($cfValue) {
                return $cfValue->customField->category === 'lainnya';
            });
        @endphp
        @if($customFieldsLainnya->count() > 0)
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-ui-checks me-2"></i>➕ Data Lainnya
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($customFieldsLainnya as $cfValue)
                    <div class="col-md-6 mb-3">
                        <strong>{{ $cfValue->customField->field_label }}:</strong>
                        @if($cfValue->customField->field_type == 'file' && $cfValue->value)
                        <br>
                        <a href="{{ asset('storage/' . $cfValue->value) }}" 
                           target="_blank" 
                           class="btn btn-sm btn-info mt-2">
                            <i class="bi bi-file-earmark me-1"></i>Lihat File
                        </a>
                        @else
                        <p class="mb-0">{{ $cfValue->value ?: '-' }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Info Timestamps -->
        <div class="card border-info">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="bi bi-calendar-plus me-1"></i>
                            Ditambahkan: {{ $karyawan->created_at->format('d M Y H:i') }}
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small class="text-muted">
                            <i class="bi bi-calendar-check me-1"></i>
                            Terakhir diupdate: {{ $karyawan->updated_at->format('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>
@endsection