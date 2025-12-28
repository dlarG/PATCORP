@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('breadcrumb-section', 'Dashboard')
@section('breadcrumb-current', 'Overview')

@section('content')
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1>Welcome back, {{ auth()->user()->first_name }}! 👋</h1>
        <p>Here's what's happening with your management system today.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card stat-card-1">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <h3>{{ $stats['total_drivers'] ?? 0 }}</h3>
            <p>Total Drivers</p>
        </div>
        
        <div class="stat-card stat-card-2">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <h3>{{ $stats['active_drivers'] ?? 0 }}</h3>
            <p>Active Drivers</p>
        </div>
        
        <div class="stat-card stat-card-3">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <h3>{{ $stats['unpaid_drivers'] ?? 0 }}</h3>
            <p>Unpaid Drivers</p>
        </div>
        
        <div class="stat-card stat-card-4">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <i class="fas fa-folder"></i>
                </div>
            </div>
            <h3>{{ $stats['total_files'] ?? 0 }}</h3>
            <p>Total Files</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2 class="section-title">Quick Actions</h2>
        <div class="action-grid">
            <a href="{{ route('drivers.create') }}" class="action-btn">
                <i class="fas fa-user-plus"></i>
                Add New Driver
            </a>
            <a href="{{ route('files.create') }}" class="action-btn">
                <i class="fas fa-upload"></i>
                Upload File
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-file-invoice-dollar"></i>
                Process Payment
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-chart-bar"></i>
                View Reports
            </a>
        </div>
    </div>
@endsection

@push('styles')
<style>
/* Add your existing dashboard-specific styles here */
.welcome-section {
    background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
    color: #1a202c;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.welcome-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

.welcome-section h1 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.welcome-section p {
    font-size: 16px;
    opacity: 0.8;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--card-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.stat-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    background: var(--card-color);
}

.stat-card h3 {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 5px;
}

.stat-card p {
    color: #718096;
    font-weight: 500;
}

.stat-card-1 { --card-color: #FFD41D; }
.stat-card-2 { --card-color: #FFA240; }
.stat-card-3 { --card-color: #D73535; }
.stat-card-4 { --card-color: #4299e1; }

/* Quick Actions */
.quick-actions {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 20px;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    background: white;
    border: 2px solid #e2e8f0;
    padding: 20px;
    border-radius: 10px;
    text-decoration: none;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s ease;
    color: #4a5568;
}

.action-btn:hover {
    border-color: #FFD41D;
    background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255,212,29,0.3);
}

.action-btn i {
    font-size: 24px;
    margin-bottom: 10px;
    display: block;
}
</style>