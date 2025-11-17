<!-- resources/views/karyawan/index.blade.php -->
@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')

@section('content')
<style>
    .sort-header {
        cursor: pointer;
        user-select: none;
        position: relative;
        padding-right: 20px;
    }
    
    .sort-header:hover {
        background-color: #f8f9fa;
    }
    
    .sort-icon {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        opacity: 0.3;
    }
    
    .sort-header.active .sort-icon {
        opacity: 1;
        color: #667eea;
    }
    
    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .stats-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Karyawan
        </a>
        <a href="{{ route('karyawan.import.form') }}" class="btn btn-success">
            <i class="bi bi-cloud-upload me-2"></i>Import Excel
        </a>
        <a href="{{ route('karyawan.export') }}" class="btn btn-info">
            <i class="bi bi-file-earmark-arrow-down me-2"></i>Export Excel
        </a>
    </div>
    <div class="stats-badge">
        <i class="bi bi-people-fill text-primary"></i>
        <span class="fw-bold">{{ $karyawans->total() }}</span>
        <span class="text-muted">Karyawan</span>
    </div>
</div>

<!-- Filter & Search Section -->
<div class="filter-section">
    <form action="{{ route('karyawan.index') }}" method="GET" class="row g-3">
        <!-- Search Input -->
        <div class="col-md-6">
            <label class="form-label fw-semibold">
                <i class="bi bi-search me-1"></i>Cari Karyawan
            </label>
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Cari nama, NIP, NIK, email, jabatan, unit..." 
                   value="{{ request('search') }}">
        </div>

        <!-- Sort By -->
        <div class="col-md-3">
            <label class="form-label fw-semibold">
                <i class="bi bi-sort-down me-1"></i>Urutkan Berdasarkan
            </label>
            <select name="sort_by" class="form-select">
                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Ditambahkan</option>
                <option value="nama_lengkap" {{ request('sort_by') == 'nama_lengkap' ? 'selected' : '' }}>Nama</option>
                <option value="nip" {{ request('sort_by') == 'nip' ? 'selected' : '' }}>NIP</option>
                <option value="nik" {{ request('sort_by') == 'nik' ? 'selected' : '' }}>NIK</option>
                <option value="jabatan" {{ request('sort_by') == 'jabatan' ? 'selected' : '' }}>Jabatan</option>
                <option value="unit" {{ request('sort_by') == 'unit' ? 'selected' : '' }}>Unit</option>
                <option value="pendidikan_terakhir" {{ request('sort_by') == 'pendidikan_terakhir' ? 'selected' : '' }}>Pendidikan</option>
                <option value="tahun_lulus" {{ request('sort_by') == 'tahun_lulus' ? 'selected' : '' }}>Tahun Lulus</option>
                <option value="jenis_kelamin" {{ request('sort_by') == 'jenis_kelamin' ? 'selected' : '' }}>Jenis Kelamin</option>
            </select>
        </div>

        <!-- Sort Order -->
        <div class="col-md-2">
            <label class="form-label fw-semibold">
                <i class="bi bi-arrow-down-up me-1"></i>Urutan
            </label>
            <select name="sort_order" class="form-select">
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                    <i class="bi bi-sort-up"></i> A → Z / Lama → Baru
                </option>
                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>
                    <i class="bi bi-sort-down"></i> Z → A / Baru → Lama
                </option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="col-md-1 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary w-100" title="Terapkan Filter">
                <i class="bi bi-funnel-fill"></i>
            </button>
        </div>
    </form>

    <!-- Active Filters Display -->
    @if(request('search') || request('sort_by') != 'created_at' || request('sort_order') != 'desc')
    <div class="mt-3 d-flex gap-2 align-items-center flex-wrap">
        <span class="text-muted small">Filter aktif:</span>
        
        @if(request('search'))
        <span class="badge bg-primary">
            <i class="bi bi-search me-1"></i>
            Pencarian: "{{ request('search') }}"
            <a href="{{ route('karyawan.index', array_filter(['sort_by' => request('sort_by'), 'sort_order' => request('sort_order')])) }}" 
               class="text-white ms-1" 
               style="text-decoration: none;">×</a>
        </span>
        @endif

        @if(request('sort_by') && request('sort_by') != 'created_at')
        <span class="badge bg-info">
            <i class="bi bi-sort-down me-1"></i>
            Sort: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
        </span>
        @endif

        @if(request('sort_order') && request('sort_order') != 'desc')
        <span class="badge bg-info">
            Order: {{ request('sort_order') == 'asc' ? 'Ascending' : 'Descending' }}
        </span>
        @endif

        <a href="{{ route('karyawan.index') }}" class="badge bg-secondary text-decoration-none">
            <i class="bi bi-x-circle me-1"></i>Reset Semua
        </a>
    </div>
    @endif
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        @if($karyawans->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th class="sort-header {{ request('sort_by') == 'nip' ? 'active' : '' }}" 
                            onclick="sortBy('nip')">
                            NIP
                            <span class="sort-icon">
                                @if(request('sort_by') == 'nip')
                                    <i class="bi bi-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-alt"></i>
                                @else
                                    <i class="bi bi-arrow-down-up"></i>
                                @endif
                            </span>
                        </th>
                        <th class="sort-header {{ request('sort_by') == 'nama_lengkap' ? 'active' : '' }}" 
                            onclick="sortBy('nama_lengkap')">
                            Nama Lengkap
                            <span class="sort-icon">
                                @if(request('sort_by') == 'nama_lengkap')
                                    <i class="bi bi-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-alt"></i>
                                @else
                                    <i class="bi bi-arrow-down-up"></i>
                                @endif
                            </span>
                        </th>
                        <th class="sort-header {{ request('sort_by') == 'jabatan' ? 'active' : '' }}" 
                            onclick="sortBy('jabatan')">
                            Jabatan
                            <span class="sort-icon">
                                @if(request('sort_by') == 'jabatan')
                                    <i class="bi bi-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-alt"></i>
                                @else
                                    <i class="bi bi-arrow-down-up"></i>
                                @endif
                            </span>
                        </th>
                        <th class="sort-header {{ request('sort_by') == 'unit' ? 'active' : '' }}" 
                            onclick="sortBy('unit')">
                            Unit
                            <span class="sort-icon">
                                @if(request('sort_by') == 'unit')
                                    <i class="bi bi-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}-alt"></i>
                                @else
                                    <i class="bi bi-arrow-down-up"></i>
                                @endif
                            </span>
                        </th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($karyawans as $karyawan)
                    <tr>
                        <td>
                            @if($karyawan->foto_profil)
                            <img src="{{ asset('storage/' . $karyawan->foto_profil) }}" 
                                 class="rounded-circle" 
                                 width="40" 
                                 height="40" 
                                 style="object-fit: cover;"
                                 alt="{{ $karyawan->nama_lengkap }}"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($karyawan->nama_lengkap) }}&background=667eea&color=fff&size=40';">
                            @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-white"></i>
                            </div>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $karyawan->nip }}</span></td>
                        <td class="fw-semibold">{{ $karyawan->nama_lengkap }}</td>
                        <td>{{ $karyawan->jabatan }}</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $karyawan->unit }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $karyawan->email }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('karyawan.show', $karyawan) }}" 
                                   class="btn btn-sm btn-info" 
                                   title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('karyawan.edit', $karyawan) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('karyawan.destroy', $karyawan) }}" 
                                      method="POST" 
                                      class="d-inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus {{ $karyawan->nama_lengkap }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger" 
                                            title="Hapus">
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
        
        <!-- Pagination with Info -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Menampilkan {{ $karyawans->firstItem() ?? 0 }} - {{ $karyawans->lastItem() ?? 0 }} 
                dari {{ $karyawans->total() }} karyawan
            </div>
            <div>
                {{ $karyawans->links() }}
            </div>
        </div>

        @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
            @if(request('search'))
            <h5>Tidak ada hasil untuk "{{ request('search') }}"</h5>
            <p class="text-muted">Coba gunakan kata kunci yang berbeda</p>
            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Pencarian
            </a>
            @else
            <h5>Belum ada data karyawan</h5>
            <p class="text-muted">Mulai tambahkan data karyawan pertama Anda</p>
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Karyawan Pertama
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Function untuk sort dengan klik header
function sortBy(column) {
    const currentUrl = new URL(window.location.href);
    const params = new URLSearchParams(currentUrl.search);
    
    const currentSortBy = params.get('sort_by') || 'created_at';
    const currentSortOrder = params.get('sort_order') || 'desc';
    
    // Toggle sort order jika klik kolom yang sama
    if (currentSortBy === column) {
        params.set('sort_order', currentSortOrder === 'asc' ? 'desc' : 'asc');
    } else {
        params.set('sort_by', column);
        params.set('sort_order', 'asc'); // Default asc untuk kolom baru
    }
    
    window.location.href = `${currentUrl.pathname}?${params.toString()}`;
}

// Keyboard shortcut untuk focus search
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K untuk focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('input[name="search"]').focus();
        }
    });
});
</script>
@endpush