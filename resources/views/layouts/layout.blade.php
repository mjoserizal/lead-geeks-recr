<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'IT Support Portal') | LeadGeeks</title>
    <meta name="description" content="Internal IT Support Portal and Ticket Dashboard. Track, assign, filter and update IT support tickets easily.">
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body id="app-body">
    <div class="dashboard-wrapper">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="app-sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <i data-lucide="shield-alert"></i>
                </div>
                <div>
                    <span class="brand-name">LeadGeeks IT</span>
                    <span class="brand-sub">Support Portal</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('tickets.index') }}" class="nav-item {{ Route::is('tickets.index') || Route::is('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('tickets.index') }}" class="nav-item">
                    <i data-lucide="ticket"></i>
                    <span>All Tickets</span>
                </a>
                <a href="#" class="nav-item disabled" onclick="return false;">
                    <i data-lucide="users"></i>
                    <span>IT Staff</span>
                </a>
                <a href="#" class="nav-item disabled" onclick="return false;">
                    <i data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-avatar">SM</div>
                <div class="user-info">
                    <span class="user-name">Sophia Martinez</span>
                    <span class="user-role">IT Administrator</span>
                </div>
            </div>
        </aside>

        <!-- Main Content Panel -->
        <main class="main-content">
            <header class="top-nav">
                <div class="search-mock">
                    <i data-lucide="search"></i>
                    <input type="text" placeholder="Quick search..." disabled style="background:transparent; border:none; color:var(--text-muted); outline:none;">
                </div>
                <div class="top-nav-actions">
                    <div class="notification-bell">
                        <i data-lucide="bell"></i>
                        <span class="notification-dot"></span>
                    </div>
                </div>
            </header>

            <div class="content-body">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Script to dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const dismissButtons = document.querySelectorAll('.alert-close');
            dismissButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const alert = this.closest('.alert-success');
                    if (alert) {
                        alert.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-10px)';
                        setTimeout(() => alert.remove(), 200);
                    }
                });
            });
        });
    </script>
</body>
</html>
