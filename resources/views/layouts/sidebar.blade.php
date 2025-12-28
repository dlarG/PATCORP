<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="/asset/logo.jpg" alt="PATCORP logo">
        </div>
        <h2>PATCORP</h2>
        <p>Management System</p>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" 
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Management</div>
            <div class="nav-item">
                <a href="{{ route('drivers.index') }}" 
                   class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Drivers</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('files.index') }}" 
                   class="nav-link {{ request()->routeIs('files.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span>Files</span>
                </a>
            </div>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Reports & Analytics</div>
            <div class="nav-item">
                <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Analytics</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="#" class="nav-link {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>System Logs</span>
                </a>
            </div>
        </div>
        <div class="nav-section">
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->first_name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <h4>{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}</h4>
                        <p>{{ ucfirst(auth()->user()->user_type ?? 'Administrator') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
</aside>