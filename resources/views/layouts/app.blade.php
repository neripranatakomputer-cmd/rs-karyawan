<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Karyawan RS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .nav-link {
            color: rgba(255,255,255,0.8);
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @if(!Request::is('/'))
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <i class="bi bi-hospital fs-1 text-white"></i>
                        <h5 class="text-white mt-2">RS Management</h5>
                    </div>
                    <ul class="nav flex-column px-2">

                        <li class="nav-item mb-2">
                            <a class="nav-link" href="{{ route('landing') }}">
                                <i class="bi bi-house-fill me-2"></i> Beranda
                            </a>
                        </li>

                        <li class="nav-item mb-2">
                            <a class="nav-link {{ Request::is('karyawan*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
                                <i class="bi bi-people-fill me-2"></i> Data Karyawan
                            </a>
                        </li>

                        <li class="nav-item mb-2">
                            <a class="nav-link {{ Request::is('custom-fields*') ? 'active' : '' }}" href="{{ route('custom-fields.index') }}">
                                <i class="bi bi-ui-checks me-2"></i> Custom Fields
                            </a>
                        </li>
                        
                        <li class="nav-item mb-2">
                            <a class="nav-link {{ Request::is('attendance*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                <i class="bi bi-calendar-check me-2"></i> Absensi
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
            @endif
                @if(!Request::is('/'))
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title')</h1>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')

            @if(!Request::is('/'))
            </main>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>