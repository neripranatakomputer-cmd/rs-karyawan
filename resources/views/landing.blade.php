<!-- resources/views/landing.blade.php -->
@extends('layouts.app')

@section('title', 'Beranda - Sistem Manajemen Karyawan RS')

@section('content')
<style>
    /* Hero Section dengan Background Image */
    .hero-section-with-image {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.70) 0%, rgba(118, 75, 162, 0.70) 100%),
                    url('/images/hospital-hero.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
        padding: 120px 0;
        position: relative;
        min-height: 600px;
        display: flex;
        align-items: center;
    }
    
    /* Badge Logo RS */
    .hospital-badge {
        width: 140px;
        height: 140px;
        margin: 0 auto 30px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        animation: fadeInDown 1s ease-out;
    }
    
    .hospital-badge img {
        max-width: 90px;
        max-height: 90px;
        object-fit: contain;
    }
    
    /* Animasi untuk konten hero */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .hero-title {
        animation: fadeInDown 1s ease-out 0.2s both;
    }
    
    .hero-subtitle {
        animation: fadeInUp 1s ease-out 0.4s both;
    }
    
    .hero-buttons {
        animation: fadeInUp 1s ease-out 0.6s both;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-section-with-image {
            padding: 80px 20px;
            background-attachment: scroll;
            min-height: 500px;
        }
        
        .hospital-badge {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }
        
        .hospital-badge img {
            max-width: 60px;
            max-height: 60px;
        }
        
        .display-3 {
            font-size: 2.2rem;
        }
        
        .lead {
            font-size: 1.1rem;
        }
    }
</style>

<!-- Hero Section dengan Background Image -->
<div class="hero-section-with-image">
    <div class="container text-center">
        <!-- Logo RS -->
        <div class="hospital-badge">
            <img src="/images/logo-rs.png" alt="Logo Rumah Sakit">
        </div>
        
        <!-- Title -->
        <h1 class="display-3 fw-bold mb-3 hero-title">Sistem Manajemen Karyawan</h1>
        <h1 class="display-4 fw-bold mb-3 hero-title">RS Umum Daerah Kamang Baru</h1>

        <!-- Subtitle -->
        <p class="lead mb-4 hero-subtitle" style="font-size: 1.4rem;">Rumah Sakit Modern & Terintegrasi</p>
        
        <!-- Call to Action Buttons -->
        <div class="d-flex gap-3 justify-content-center flex-wrap hero-buttons">
            <a href="{{ route('karyawan.index') }}" class="btn btn-light btn-lg px-5 shadow-lg">
                <i class="bi bi-people-fill me-2"></i>Kelola Karyawan
            </a>
            <a href="{{ route('karyawan.create') }}" class="btn btn-outline-light btn-lg px-5">
                <i class="bi bi-plus-circle me-2"></i>Tambah Karyawan
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card text-center p-4 h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-people text-primary fs-1 mb-3"></i>
                    <h3 class="display-4 fw-bold text-primary">{{ $totalKaryawan }}</h3>
                    <p class="text-muted mb-0">Total Karyawan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-clipboard-check text-success fs-1 mb-3"></i>
                    <h3 class="display-4 fw-bold text-success">100%</h3>
                    <p class="text-muted mb-0">Data Terverifikasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 h-100 border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-shield-check text-info fs-1 mb-3"></i>
                    <h3 class="display-4 fw-bold text-info">Aman</h3>
                    <p class="text-muted mb-0">Data Terenkripsi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Employees -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">Karyawan Terbaru</h2>
        <p class="text-muted">Tim profesional kami yang berdedikasi</p>
    </div>
    
    <div class="row g-4">
        @forelse($recentKaryawan as $karyawan)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    @if($karyawan->foto_profil)
                    <img src="{{ asset('storage/' . $karyawan->foto_profil) }}" 
                         class="rounded-circle mb-3 shadow" 
                         width="100" 
                         height="100" 
                         style="object-fit: cover; border: 4px solid #f8f9fa;">
                    @else
                    <div class="rounded-circle bg-gradient d-inline-flex align-items-center justify-content-center mb-3 shadow" 
                         style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 4px solid #f8f9fa;">
                        <i class="bi bi-person fs-1 text-white"></i>
                    </div>
                    @endif
                    
                    <h5 class="card-title mb-2">{{ $karyawan->nama_lengkap }}</h5>
                    <p class="text-primary mb-1 fw-semibold">{{ $karyawan->jabatan }}</p>
                    <p class="text-muted small mb-3">{{ $karyawan->unit }}</p>
                    
                    <a href="{{ route('karyawan.show', $karyawan) }}" class="btn btn-sm btn-primary px-4">
                        <i class="bi bi-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center border-0 shadow-sm">
                <i class="bi bi-info-circle me-2"></i>Belum ada data karyawan. 
                <a href="{{ route('karyawan.create') }}" class="alert-link">Tambah karyawan pertama</a>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p class="mb-2">&copy; {{ date('Y') }} Sistem Manajemen Karyawan Rumah Sakit</p>
        <p class="mb-0 text-muted small">Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk pelayanan kesehatan yang lebih baik</p>
    </div>
</footer>
@endsection
