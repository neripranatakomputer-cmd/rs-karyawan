<!-- resources/views/attendance/rekap.blade.php -->
@extends('layouts.app')

@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi Karyawan')

@section('content')
<style>
    .rekap-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .rekap-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
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
                <label class="form-label">Unit</label>
                <select name="unit" class="form-select">
                    <option value="">Semua Unit</option>
                    @foreach($units as $u)
                    <option value="{{ $u }}" {{ $unit == $u ? 'selected' : '' }}>{{ $u }}</option>
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

<!-- Summary Cards -->
@php
    $totalKaryawan = $karyawans->count();
    $totalHadir = $karyawans->sum(function($k) use ($month, $year) {
        return $k->attendances->where('status', 'hadir')->count();
    });
    $totalIzin = $karyawans->sum(function($k) use ($month, $year) {
        return $k->attendances->where('status', 'izin')->count();
    });
    $totalSakit = $karyawans->sum(function($k) use ($month, $year) {
        return $k->attendances->where('status', 'sakit')->count();
    });
    $totalCuti = $karyawans->sum(function($k) use ($month, $year) {
        return $k->attendances->where('status', 'cuti')->count();
    });
    $totalAlpa = $karyawans->sum(function($k) use ($month, $year) {
        return $k->attendances->where('status', 'alpa')->count();
    });
    $totalWFH = $karyawans->sum(function($k) use ($month, $year) {
        return $k->attendances->where('status', 'wfh')->count();
    });
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="card text-center border-success rekap-card" style="border-left-color: #28a745;">
            <div class="card-body">
                <i class="bi bi-check-circle text-success fs-1"></i>
                <h3 class="mt-2 mb-0">{{ $totalHadir }}</h3>
                <small class="text-muted">Hadir</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-warning rekap-card" style="border-left-color: #ffc107;">
            <div class="card-body">
                <i class="bi bi-file-text text-warning fs-1"></i>
                <h3 class="mt-2 mb-0">{{ $totalIzin }}</h3>
                <small class="text-muted">Izin</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-danger rekap-card" style="border-left-color: #dc3545;">
            <div class="card-body">
                <i class="bi bi-heart-pulse text-danger fs-1"></i>
                <h3 class="mt-2 mb-0">{{ $totalSakit }}</h3>
                <small class="text-muted">Sakit</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-info rekap-card" style="border-left-color: #17a2b8;">
            <div class="card-body">
                <i class="bi bi-calendar-x text-info fs-1"></i>
                <h3 class="mt-2 mb-0">{{ $totalCuti }}</h3>
                <small class="text-muted">Cuti</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-dark rekap-card" style="border-left-color: #343a40;">
            <div class="card-body">
                <i class="bi bi-x-circle text-dark fs-1"></i>
                <h3 class="mt-2 mb-0">{{ $totalAlpa }}</h3>
                <small class="text-muted">Alpa</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-primary rekap-card" style="border-left-color: #007bff;">
            <div class="card-body">
                <i class="bi bi-house text-primary fs-1"></i>
                <h3 class="mt-2 mb-0">{{ $totalWFH }}</h3>
                <small class="text-muted">WFH</small>
            </div>
        </div>
    </div>
</div>

<!-- Rekap Per Karyawan -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-4">
            <i class="bi bi-people me-2"></i>Rekap Per Karyawan
        </h5>

        @if($karyawans->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Karyawan</th>
                        <th>Unit</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Cuti</th>
                        <th class="text-center">Alpa</th>
                        <th class="text-center">WFH</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($karyawans as $index => $karyawan)
                    @php
                        $rekap = $karyawan->getRekapAbsensi($month, $year);
                        $totalHariKerja = now()->month == $month && now()->year == $year 
                            ? now()->day 
                            : \Carbon\Carbon::create($year, $month)->daysInMonth;
                        $persentase = $totalHariKerja > 0 
                            ? round(($rekap['hadir'] / $totalHariKerja) * 100, 1) 
                            : 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $karyawan->nama_lengkap }}</strong><br>
                            <small class="text-muted">{{ $karyawan->nip }}</small>
                        </td>
                        <td>{{ $karyawan->unit }}</td>
                        <td class="text-center">
                            <span class="badge bg-success">{{ $rekap['hadir'] }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning">{{ $rekap['izin'] }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger">{{ $rekap['sakit'] }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $rekap['cuti'] }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark">{{ $rekap['alpa'] }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $rekap['wfh'] }}</span>
                        </td>
                        <td class="text-center">
                            <strong>{{ $rekap['total'] }}</strong>
                        </td>
                        <td class="text-center">
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" 
                                     role="progressbar" 
                                     style="width: {{ $persentase }}%;" 
                                     aria-valuenow="{{ $persentase }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $persentase }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                        <td class="text-center"><strong>{{ $totalHadir }}</strong></td>
                        <td class="text-center"><strong>{{ $totalIzin }}</strong></td>
                        <td class="text-center"><strong>{{ $totalSakit }}</strong></td>
                        <td class="text-center"><strong>{{ $totalCuti }}</strong></td>
                        <td class="text-center"><strong>{{ $totalAlpa }}</strong></td>
                        <td class="text-center"><strong>{{ $totalWFH }}</strong></td>
                        <td class="text-center"><strong>{{ $totalHadir + $totalIzin + $totalSakit + $totalCuti + $totalAlpa + $totalWFH }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Export Button -->
        <div class="mt-3">
            <button class="btn btn-success" onclick="printRekap()">
                <i class="bi bi-printer me-2"></i>Print Rekap
            </button>
            <button class="btn btn-info" onclick="exportExcel()">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
            </button>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
            <h5>Tidak ada data karyawan</h5>
            <p class="text-muted">untuk periode yang dipilih</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function printRekap() {
    window.print();
}

function exportExcel() {
    // TODO: Implement Excel export
    alert('Fitur export Excel akan segera ditambahkan');
}
</script>
@endpush