<!-- resources/views/karyawan/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Data Karyawan')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('karyawan.update', $karyawan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- 1️⃣ Data Pribadi -->
            <h5 class="mb-3 text-primary border-bottom pb-2">
                <i class="bi bi-person-badge me-2"></i>1️⃣ Data Pribadi
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Foto Profil</label>
                    @if($karyawan->foto_profil)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $karyawan->foto_profil) }}" 
                             class="rounded" 
                             width="100"
                             alt="Foto Profil"
                             onerror="this.style.display='none';">
                    </div>
                    @endif
                    <input type="file" name="foto_profil" class="form-control @error('foto_profil') is-invalid @enderror" accept="image/*">
                    @error('foto_profil')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $karyawan->nip) }}" required>
                    @error('nip')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $karyawan->nik) }}" maxlength="16" required>
                    @error('nik')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
                    @error('nama_lengkap')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama dengan Gelar</label>
                    <input type="text" name="nama_gelar" class="form-control @error('nama_gelar') is-invalid @enderror" value="{{ old('nama_gelar', $karyawan->nama_gelar) }}">
                    @error('nama_gelar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">Pilih...</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Custom Fields - Data Pribadi -->
                @php
                    $customFieldsDataPribadi = $customFields->where('category', 'data_pribadi');
                @endphp
                @foreach($customFieldsDataPribadi as $field)
                <div class="col-md-6">
                    <label class="form-label">
                        {{ $field->field_label }}
                        @if($field->is_required) <span class="text-danger">*</span> @endif
                    </label>
                    @include('karyawan.partials.custom-field-input', [
                        'field' => $field, 
                        'value' => $customFieldValues[$field->id] ?? ''
                    ])
                </div>
                @endforeach
            </div>

            <!-- 2️⃣ Data Kontak -->
            <h5 class="mb-3 text-success border-bottom pb-2">
                <i class="bi bi-telephone me-2"></i>2️⃣ Data Kontak
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">No. HP <span class="text-danger">*</span></label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $karyawan->no_hp) }}" required>
                    @error('no_hp')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $karyawan->email) }}" required>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required>{{ old('alamat', $karyawan->alamat) }}</textarea>
                    @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Custom Fields - Data Kontak -->
                @php
                    $customFieldsDataKontak = $customFields->where('category', 'data_kontak');
                @endphp
                @foreach($customFieldsDataKontak as $field)
                <div class="col-md-6">
                    <label class="form-label">
                        {{ $field->field_label }}
                        @if($field->is_required) <span class="text-danger">*</span> @endif
                    </label>
                    @include('karyawan.partials.custom-field-input', [
                        'field' => $field, 
                        'value' => $customFieldValues[$field->id] ?? ''
                    ])
                </div>
                @endforeach
            </div>

            <!-- 3️⃣ Data Pendidikan -->
            <h5 class="mb-3 text-info border-bottom pb-2">
                <i class="bi bi-mortarboard me-2"></i>3️⃣ Data Pendidikan
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                    <select name="pendidikan_terakhir" class="form-select @error('pendidikan_terakhir') is-invalid @enderror" required>
                        <option value="">Pilih...</option>
                        <option value="SMA/SMK" {{ old('pendidikan_terakhir', $karyawan->pendidikan_terakhir) == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                        <option value="D3" {{ old('pendidikan_terakhir', $karyawan->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="S1" {{ old('pendidikan_terakhir', $karyawan->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('pendidikan_terakhir', $karyawan->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('pendidikan_terakhir', $karyawan->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                    @error('pendidikan_terakhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tahun Lulus <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_lulus" class="form-control @error('tahun_lulus') is-invalid @enderror" value="{{ old('tahun_lulus', $karyawan->tahun_lulus) }}" min="1950" max="{{ date('Y') }}" required>
                    @error('tahun_lulus')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload Ijazah</label>
                    @if($karyawan->ijazah)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $karyawan->ijazah) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="bi bi-file-earmark"></i> Lihat File
                        </a>
                    </div>
                    @endif
                    <input type="file" name="ijazah" class="form-control @error('ijazah') is-invalid @enderror" accept=".pdf,image/*">
                    @error('ijazah')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah file</small>
                </div>

                <!-- Custom Fields - Data Pendidikan -->
                @php
                    $customFieldsDataPendidikan = $customFields->where('category', 'data_pendidikan');
                @endphp
                @foreach($customFieldsDataPendidikan as $field)
                <div class="col-md-6">
                    <label class="form-label">
                        {{ $field->field_label }}
                        @if($field->is_required) <span class="text-danger">*</span> @endif
                    </label>
                    @include('karyawan.partials.custom-field-input', [
                        'field' => $field, 
                        'value' => $customFieldValues[$field->id] ?? ''
                    ])
                </div>
                @endforeach
            </div>

            <!-- 4️⃣ Data Kepegawaian -->
            <h5 class="mb-3 text-warning border-bottom pb-2">
                <i class="bi bi-briefcase me-2"></i>4️⃣ Data Kepegawaian
            </h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $karyawan->jabatan) }}" required>
                    @error('jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unit <span class="text-danger">*</span></label>
                    <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $karyawan->unit) }}" required>
                    @error('unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload SIP</label>
                    @if($karyawan->sip)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $karyawan->sip) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="bi bi-file-earmark"></i> Lihat File
                        </a>
                    </div>
                    @endif
                    <input type="file" name="sip" class="form-control @error('sip') is-invalid @enderror" accept=".pdf,image/*">
                    @error('sip')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Upload STR</label>
                    @if($karyawan->str)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $karyawan->str) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="bi bi-file-earmark"></i> Lihat File
                        </a>
                    </div>
                    @endif
                    <input type="file" name="str" class="form-control @error('str') is-invalid @enderror" accept=".pdf,image/*">
                    @error('str')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Custom Fields - Data Kepegawaian -->
                @php
                    $customFieldsDataKepegawaian = $customFields->where('category', 'data_kepegawaian');
                @endphp
                @foreach($customFieldsDataKepegawaian as $field)
                <div class="col-md-6">
                    <label class="form-label">
                        {{ $field->field_label }}
                        @if($field->is_required) <span class="text-danger">*</span> @endif
                    </label>
                    @include('karyawan.partials.custom-field-input', [
                        'field' => $field, 
                        'value' => $customFieldValues[$field->id] ?? ''
                    ])
                </div>
                @endforeach
            </div>

            <!-- ➕ Data Lainnya (jika ada) -->
            @php
                $customFieldsLainnya = $customFields->where('category', 'lainnya');
            @endphp
            @if($customFieldsLainnya->count() > 0)
            <h5 class="mb-3 text-secondary border-bottom pb-2">
                <i class="bi bi-ui-checks me-2"></i>➕ Data Lainnya
            </h5>
            <div class="row g-3 mb-4">
                @foreach($customFieldsLainnya as $field)
                <div class="col-md-6">
                    <label class="form-label">
                        {{ $field->field_label }}
                        @if($field->is_required) <span class="text-danger">*</span> @endif
                    </label>
                    @include('karyawan.partials.custom-field-input', [
                        'field' => $field, 
                        'value' => $customFieldValues[$field->id] ?? ''
                    ])
                </div>
                @endforeach
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Update
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection