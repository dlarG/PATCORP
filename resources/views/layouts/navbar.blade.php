<header class="top-header">
    <div class="header-left">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="breadcrumb">
            <span>@yield('breadcrumb-section', 'Dashboard')</span>
            <i class="fas fa-chevron-right"></i>
            <span class="current">@yield('breadcrumb-current', 'Overview')</span>
        </div>
    </div>
    <div class="header-right">
        <button class="notification-bell">
            <i class="fas fa-bell"></i>
            <div class="notification-badge"></div>
        </button>
        <div class="header-user-info">
            <span>{{ now()->format('M d, Y') }}</span>
        </div>
    </div>
</header>