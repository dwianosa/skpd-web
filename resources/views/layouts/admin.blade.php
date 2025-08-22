<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - SKPD Kominfo Bukittinggi')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <!-- Kominfo Icon -->
                <div class="kominfo-icon me-3">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Spiral design -->
                        <path d="M20 2C20 2 18 4 16 8C14 12 12 18 12 20C12 22 14 26 18 28C22 30 28 30 32 28C36 26 38 22 38 20C38 18 36 14 32 12C28 10 22 10 18 12C14 14 12 18 12 20" stroke="url(#kominfoGradient)" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path d="M20 6C20 6 18 8 16 12C14 16 12 18 12 20C12 22 14 24 18 26C22 28 28 28 32 26C36 24 38 22 38 20C38 18 36 16 32 14C28 12 22 12 18 14C14 16 12 18 12 20" stroke="url(#kominfoGradient)" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <path d="M20 10C20 10 18 12 16 16C14 20 12 20 12 20C12 20 14 22 18 24C22 26 28 26 32 24C36 22 38 20 38 20C38 20 36 18 32 16C28 14 22 14 18 16C14 18 12 20 12 20" stroke="url(#kominfoGradient)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="kominfoGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#1e3a8a;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#3b82f6;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div>
                    <strong>Admin SKPD</strong><br>
                    <small>Kominfo Bukittinggi</small>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}" 
                           href="{{ route('admin.surat.index') }}">
                            <i class="fas fa-file-alt"></i> Kelola Surat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jenis-surat.*') ? 'active' : '' }}" 
                           href="{{ route('admin.jenis-surat.index') }}">
                            <i class="fas fa-list"></i> Jenis Surat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.demo.email') ? 'active' : '' }}" 
                           href="{{ route('admin.demo.email') }}">
                            <i class="fas fa-envelope"></i> Demo Email
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Lihat Website
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-edit"></i> Profil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog"></i> Pengaturan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <i class="fas fa-exclamation-triangle"></i> 
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="min-vh-100" style="background-color: #f8f9fa;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-3 mt-auto">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <!-- Kominfo Icon in Footer -->
                        <div class="kominfo-icon me-2">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Spiral design -->
                                <path d="M10 1C10 1 9 2 7.5 4C6 6 4.5 9 4.5 10C4.5 11 6 13 7.5 14C9 15 12 15 13.5 14C15 13 16.5 11 16.5 10C16.5 9 15 7 13.5 6C12 5 9 5 7.5 6C6 7 4.5 9 4.5 10" stroke="url(#kominfoGradientSmall)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                                <path d="M10 3C10 3 9 4 7.5 6C6 8 4.5 9 4.5 10C4.5 11 6 12 7.5 13C9 14 12 14 13.5 13C15 12 16.5 11 16.5 10C16.5 9 15 8 13.5 7C12 6 9 6 7.5 7C6 8 4.5 9 4.5 10" stroke="url(#kominfoGradientSmall)" stroke-width="1" fill="none" stroke-linecap="round"/>
                                <path d="M10 5C10 5 9 6 7.5 8C6 10 4.5 10 4.5 10C4.5 10 6 11 7.5 12C9 13 12 13 13.5 12C15 11 16.5 10 16.5 10C16.5 10 15 9 13.5 8C12 7 9 7 7.5 8C6 9 4.5 10 4.5 10" stroke="url(#kominfoGradientSmall)" stroke-width="0.8" fill="none" stroke-linecap="round"/>
                                <defs>
                                    <linearGradient id="kominfoGradientSmall" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#1e3a8a;stop-opacity:1" />
                                        <stop offset="50%" style="stop-color:#3b82f6;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#06b6d4;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <small>&copy; {{ date('Y') }} Kominfo Bukittinggi. Admin Panel v1.0</small>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <small>
                        <i class="fas fa-clock"></i> 
                        Login terakhir: {{ Auth::user()->updated_at->format('d M Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Confirm delete actions
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $(this).closest('form').submit();
            }
        });

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    
    @stack('scripts')
</body>
</html>