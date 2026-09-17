<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIAP PKL Kecamatan Cikampek')</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body class="bg-light">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    @yield('content')

    <!-- Toast Notification -->
    @if(session('success'))
    <div class="toast toast-success" id="toastMessage">
        <i class="ph ph-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="toast toast-error" id="toastMessage">
        <i class="ph ph-x-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <script>
        // Simple toast auto-hide
        document.addEventListener("DOMContentLoaded", () => {
            const toast = document.getElementById('toastMessage');
            if(toast) {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }
        });

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.student-sidebar, .sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
