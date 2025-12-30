<!-- resources/views/attendance/index.blade.php -->
@extends('layouts.app')

@section('title', 'Data Absensi')
@section('page-title', 'Data Absensi Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex gap-2">
        <a href="{{ route('attendance.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Input Absensi
        </a>
        <a href="{{ route('attendance.rekap') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-bar-graph me-2"></i>Rekap Absensi
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Bulan</label>
                <select name="month" class="form-select">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun</label>
                <select name="year" class="form-select">
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Karyawan</label>
                <select name="karyawan_id" class="form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($karyawans as $k)
                    <option value="{{ $k->id }}" {{ $karyawanId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_lengkap }} ({{ $k->nip }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        @if($attendances->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    @php
                        $statusInfo = $attendance->getStatusInfo();
                    @endphp
                    <tr>
                        <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $attendance->karyawan->nama_lengkap }}</strong><br>
                            <small class="text-muted">{{ $attendance->karyawan->unit }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $statusInfo['color'] }}">
                                <i class="bi bi-{{ $statusInfo['icon'] }} me-1"></i>
                                {{ $statusInfo['label'] }}
                            </span>
                        </td>
                        <td>{{ $attendance->jam_masuk ?? '-' }}</td>
                        <td>{{ $attendance->jam_keluar ?? '-' }}</td>
                        <td>
                            @if($attendance->keterangan)
                            <small>{{ Str::limit($attendance->keterangan, 50) }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('attendance.edit', $attendance) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('attendance.destroy', $attendance) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Yakin hapus absensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $attendances->links() }}
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-calendar-x fs-1 text-muted mb-3 d-block"></i>
            <h5>Belum ada data absensi</h5>
            <p class="text-muted">untuk periode yang dipilih</p>
            <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Input Absensi
            </a>
        </div>
        @endif
    </div>
</div>
@endsection