@extends('layouts.admin')

@section('title', 'Admin Analytics')
@section('breadcrumb-section', 'Reports & Analytics')
@section('breadcrumb-current', 'Analytics')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    :root {
        --primary: #FFD41D;
        --primary-dark: #e6bf1a;
        --secondary: #FFA240;
        --accent: #D73535;
        --dark: #1e293b;
        --dark-light: #334155;
        --light: #f8fafc;
        --gray: #64748b;
        --gray-light: #e2e8f0;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --surface: #ffffff;
        --shadow: rgba(0, 0, 0, 0.08);
        --shadow-hover: rgba(0, 0, 0, 0.12);
    }

    .analytics-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .analytics-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: #1a202c;
        padding: clamp(20px, 4vw, 30px);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(255, 212, 29, 0.2);
    }

    .analytics-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .header-text h1 {
        font-size: clamp(1.5rem, 5vw, 2rem);
        font-weight: 800;
        margin-bottom: 8px;
        background: linear-gradient(90deg, #1a202c 0%, #2d3748 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-text p {
        color: rgba(26, 32, 44, 0.8);
        font-size: clamp(0.9rem, 3vw, 1rem);
        max-width: 600px;
    }

    .export-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        padding: clamp(10px, 2vw, 12px) clamp(15px, 3vw, 20px);
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: clamp(0.85rem, 2.5vw, 0.9rem);
        white-space: nowrap;
    }

    .btn-primary { 
        background: var(--dark); 
        color: white;
        box-shadow: 0 4px 15px rgba(30, 41, 59, 0.2);
    }
    
    .btn-secondary { 
        background: var(--gray); 
        color: white;
        box-shadow: 0 4px 15px rgba(100, 116, 139, 0.2);
    }
    
    .btn-success { 
        background: var(--success); 
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--surface);
        border-radius: 20px;
        padding: clamp(20px, 4vw, 30px);
        box-shadow: 0 8px 25px var(--shadow);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-progress-item {
        margin-bottom: 30px;
    }

    .analytics-user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        justify-content: space-between;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        opacity: 0.8;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255, 212, 29, 0.03) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px var(--shadow-hover);
    }

    .stat-card:hover::after {
        opacity: 1;
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .stat-icon {
        width: clamp(50px, 8vw, 60px);
        height: clamp(50px, 8vw, 60px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(20px, 4vw, 24px);
        color: white;
        position: relative;
        z-index: 1;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-icon.files { background: linear-gradient(135deg, var(--info), #1e40af); }
    .stat-icon.users { background: linear-gradient(135deg, var(--success), #047857); }
    .stat-icon.downloads { background: linear-gradient(135deg, var(--warning), #d97706); }
    .stat-icon.storage { background: linear-gradient(135deg, var(--accent), #b91c1c); }

    .trend-indicator {
        background: rgba(255, 255, 255, 0.9);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        backdrop-filter: blur(10px);
    }

    .trend-indicator.positive { color: var(--success); }
    .trend-indicator.negative { color: var(--danger); }
    .trend-indicator.neutral { color: var(--gray); }

    .stat-number {
        font-size: clamp(2rem, 6vw, 2.5rem);
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 8px;
        line-height: 1;
        background: linear-gradient(90deg, var(--dark), var(--dark-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: var(--gray);
        font-size: clamp(0.85rem, 2.5vw, 0.95rem);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        display: block;
    }

    .stat-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--gray-light);
        margin-top: 15px;
    }

    .stat-detail {
        font-size: 0.85rem;
        color: var(--gray);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .chart-container {
        background: var(--surface);
        border-radius: 20px;
        padding: clamp(20px, 4vw, 30px);
        box-shadow: 0 8px 25px var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .chart-title {
        font-size: clamp(1.2rem, 4vw, 1.5rem);
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chart-title i {
        color: var(--primary);
        font-size: 1.3em;
    }

    .secondary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .analytics-table {
        background: var(--surface);
        border-radius: 20px;
        padding: clamp(20px, 4vw, 30px);
        box-shadow: 0 8px 25px var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 12px;
        margin-top: 20px;
    }

    .table {
        width: 100%;
        min-width: 600px;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }

    .table th,
    .table td {
        padding: clamp(12px, 3vw, 15px);
        text-align: left;
        border-bottom: 1px solid var(--gray-light);
        white-space: nowrap;
    }

    .table th {
        background: var(--light);
        font-weight: 700;
        color: var(--dark);
        text-transform: uppercase;
        font-size: clamp(0.75rem, 2vw, 0.85rem);
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
    }

    .table tr:hover {
        background: linear-gradient(90deg, rgba(255, 212, 29, 0.05) 0%, transparent 100%);
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: var(--gray-light);
        border-radius: 4px;
        overflow: hidden;
        margin: 10px 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        border-radius: 4px;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .file-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: clamp(0.75rem, 2.5vw, 0.8rem);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-pdf { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }
    .badge-image { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-document { background: rgba(59, 130, 246, 0.1); color: var(--info); border: 1px solid rgba(59, 130, 246, 0.2); }
    .badge-other { background: rgba(107, 114, 128, 0.1); color: var(--gray); border: 1px solid rgba(107, 114, 128, 0.2); }

    .user-rank {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rank-medal {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; }
    .rank-2 { background: linear-gradient(135deg, #C0C0C0, #A0A0A0); color: #000; }
    .rank-3 { background: linear-gradient(135deg, #CD7F32, #A0522D); color: #fff; }

    .empty-state {
        text-align: center;
        color: var(--gray);
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.3;
    }

    .loading-spinner {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: var(--primary);
    }

    .loading-spinner i {
        font-size: 2rem;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Ultra-small devices (300px and below) */
    @media (max-width: 300px) {
        .analytics-container {
            padding: 10px;
        }

        .analytics-header {
            padding: 15px;
            border-radius: 15px;
        }

        .header-content {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }

        .export-buttons {
            flex-direction: column;
            gap: 8px;
        }

        .btn {
            width: 100%;
            justify-content: center;
            padding: 12px;
        }

        .stat-card {
            padding: 20px 15px;
            border-radius: 15px;
        }

        .stat-header {
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 18px;
        }

        .stat-number {
            font-size: 1.8rem;
            text-align: center;
        }

        .stat-label {
            text-align: center;
        }

        .chart-container,
        .analytics-table {
            padding: 15px;
            border-radius: 15px;
        }

        .chart-title {
            font-size: 1.1rem;
            justify-content: center;
            text-align: center;
        }

        .table th,
        .table td {
            padding: 10px 8px;
            font-size: 0.8rem;
        }

        .file-type-badge {
            padding: 4px 10px;
            font-size: 0.7rem;
        }
    }

    /* Small devices (301px to 480px) */
    @media (min-width: 301px) and (max-width: 480px) {
        .analytics-container {
            padding: 15px;
        }

        .analytics-header {
            padding: 20px;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .export-buttons {
            justify-content: center;
        }

        .stat-card {
            padding: 25px 20px;
        }

        .chart-container,
        .analytics-table {
            padding: 20px;
        }
    }

    /* Medium devices (481px to 768px) */
    @media (min-width: 481px) and (max-width: 768px) {
        .analytics-header {
            padding: 25px;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .export-buttons {
            justify-content: center;
        }
    }

    /* Large devices (769px and up) */
    @media (min-width: 769px) {
        .analytics-grid {
            grid-template-columns: 1fr 1fr;
        }

        .secondary-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Print styles */
    @media print {
        .analytics-header {
            background: none;
            color: #000;
            box-shadow: none;
        }

        .btn {
            display: none;
        }

        .stat-card,
        .chart-container,
        .analytics-table {
            box-shadow: none;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="analytics-container">
    <div class="analytics-header">
        <div class="header-content">
            <div class="header-text">
                <h1>Analytics Dashboard</h1>
                <p>Real-time insights and performance metrics for your file management system</p>
            </div>
            <div class="export-buttons">
                <a href="{{ route('analytics.export', ['type' => 'overview']) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    <span>Export Overview</span>
                </a>
                <a href="{{ route('analytics.export', ['type' => 'files']) }}" class="btn btn-secondary">
                    <i class="fas fa-file-csv"></i>
                    <span>Files Data</span>
                </a>
                <a href="{{ route('analytics.export', ['type' => 'users']) }}" class="btn btn-success">
                    <i class="fas fa-users"></i>
                    <span>Users Data</span>
                </a>
            </div>
        </div>
    </div>

    <div class="stats-overview">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon files">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="trend-indicator positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $analytics['overview']['today_files'] }} today
                </div>
            </div>
            <div class="stat-number">{{ number_format($analytics['overview']['total_files']) }}</div>
            <div class="stat-label">Total Files</div>
            <div class="stat-meta">
                <div class="stat-detail">
                    Monthly trend
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="trend-indicator neutral">
                    <i class="fas fa-info-circle"></i>
                    {{ number_format($analytics['overview']['files_per_user'], 1) }} files/user
                </div>
            </div>
            <div class="stat-number">{{ number_format($analytics['overview']['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-meta">
                <div class="stat-detail">
                    <i class="fas fa-user-plus"></i>
                    Active users
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon downloads">
                    <i class="fas fa-download"></i>
                </div>
                <div class="trend-indicator positive">
                    <i class="fas fa-arrow-up"></i>
                    +{{ $analytics['overview']['today_downloads'] }} today
                </div>
            </div>
            <div class="stat-number">{{ number_format($analytics['overview']['total_downloads']) }}</div>
            <div class="stat-label">Total Downloads</div>
            <div class="stat-meta">
                <div class="stat-detail">
                    <i class="fas fa-bolt"></i>
                    Active downloads
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon storage">
                    <i class="fas fa-hdd"></i>
                </div>
                <div class="trend-indicator neutral">
                    <i class="fas fa-info-circle"></i>
                    {{ number_format($analytics['overview']['avg_file_size'] / (1024*1024), 1) }}MB avg
                </div>
            </div>
            <div class="stat-number">{{ number_format($analytics['overview']['total_storage'] / (1024*1024), 1) }}MB</div>
            <div class="stat-label">Storage Used</div>
            <div class="stat-meta">
                <div class="stat-detail">
                    <i class="fas fa-database"></i>
                    Storage metrics
                </div>
            </div>
        </div>
    </div>

    <div class="analytics-grid">
        <div class="chart-container">
            <h3 class="chart-title">
                Monthly Upload Trends
            </h3>
            <canvas id="monthlyUploadsChart" width="400" height="200"></canvas>
        </div>
        <div class="chart-container">
            <h3 class="chart-title">
                File Types Distribution
            </h3>
            <canvas id="fileTypesChart" width="200" height="200"></canvas>
        </div>
    </div>

    <div class="secondary-grid">
        <div class="chart-container">
            <h3 class="chart-title">
                Top Storage Users
            </h3>
            
            @foreach($analytics['storage_usage']['by_user']->take(5) as $index => $user)
                <div class="user-progress-item">
                    <div class="analytics-user-info">
                        <div class="user-rank">
                            <div class="rank-medal rank-{{ $index + 1 }}">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="user-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="storage-amount">
                            {{ number_format($user->storage_used / (1024*1024), 1) }}MB
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ ($user->storage_used / $analytics['storage_usage']['by_user']->max('storage_used')) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="chart-container">
            <h3 class="chart-title">
                Files by Category
            </h3>
            <canvas id="categoryChart" width="300" height="300"></canvas>
        </div>
    </div>

    <div class="analytics-table">
        <h3 class="chart-title">
            Most Downloaded Files
        </h3>
        
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Category</th>
                        <th>Uploaded By</th>
                        <th>Downloads</th>
                        <th>Size</th>
                        <th>Upload Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($analytics['top_files'] as $file)
                        <tr>
                            <td>
                                <div class="file-info">
                                    <i class="fas fa-file-alt" style="color: var(--primary); margin-right: 8px;"></i>
                                    <strong>{{ Str::limit($file->original_filename, 25) }}</strong>
                                </div>
                            </td>
                            <td>
                                @php
                                    $categoryName = strtolower($file->category->category_name ?? 'other');
                                    $categoryName = in_array($categoryName, ['pdf', 'image', 'document', 'other']) ? $categoryName : 'other';
                                @endphp
                                <span class="file-type-badge badge-{{ $categoryName }}">
                                    <i class="fas fa-{{ $categoryName == 'pdf' ? 'file-pdf' : ($categoryName == 'image' ? 'file-image' : ($categoryName == 'document' ? 'file-alt' : 'file')) }}"></i>
                                    {{ $file->category->category_name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>{{ $file->uploadedBy->first_name }} {{ $file->uploadedBy->last_name }}</td>
                            <td>
                                <strong style="color: var(--success);">{{ number_format($file->download_count) }}</strong>
                            </td>
                            <td>{{ number_format($file->file_size / (1024*1024), 1) }}MB</td>
                            <td>{{ $file->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No files found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="secondary-grids">
        <div class="analytics-table">
            <h3 class="chart-title">
                Most Active Users (30 days)
            </h3>
            
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Activities</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analytics['user_rankings']['most_active']->take(5) as $user)
                            <tr>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td><strong>{{ number_format($user->activity_count) }}</strong></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <i class="fas fa-chart-line"></i>
                                    <p>No data available</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="analytics-table">
            <h3 class="chart-title">
                <i class="fas fa-trophy"></i>
                Top Uploaders
            </h3>
            
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>User</th>
                            <th>Uploads</th>
                            <th>Storage Used</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analytics['user_rankings']['top_uploaders']->take(5) as $index => $user)
                            <tr>
                                <td>
                                    <div class="rank-medal rank-{{ $index + 1 }}">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td><strong>{{ number_format($user->upload_count) }}</strong></td>
                                <td>{{ number_format($user->storage_used / (1024*1024), 1) }}MB</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i class="fas fa-user-slash"></i>
                                    <p>No data available</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Uploads Chart
    const monthlyUploadsCtx = document.getElementById('monthlyUploadsChart').getContext('2d');
    const monthlyUploadsChart = new Chart(monthlyUploadsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($analytics['monthly_uploads']->pluck('month_name')) !!},
            datasets: [{
                label: 'File Uploads',
                data: {!! json_encode($analytics['monthly_uploads']->pluck('count')) !!},
                borderColor: '#FFD41D',
                backgroundColor: 'rgba(255, 212, 29, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#FFD41D',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(30, 41, 59, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#FFD41D',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    },
                    ticks: {
                        color: '#64748b'
                    }
                }
            }
        }
    });

    // File Types Chart
    const fileTypesCtx = document.getElementById('fileTypesChart').getContext('2d');
    const fileTypesChart = new Chart(fileTypesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($analytics['file_stats']['by_type']->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode($analytics['file_stats']['by_type']->pluck('count')) !!},
                backgroundColor: [
                    '#FFD41D',
                    '#FFA240',
                    '#D73535',
                    '#10b981',
                    '#3b82f6',
                    '#f59e0b'
                ],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        color: '#64748b',
                        font: {
                            size: window.innerWidth < 480 ? 10 : 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 41, 59, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#FFD41D',
                    borderWidth: 1
                }
            },
            cutout: '60%'
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'polarArea',
        data: {
            labels: {!! json_encode($analytics['category_distribution']->pluck('category_name')) !!},
            datasets: [{
                data: {!! json_encode($analytics['category_distribution']->pluck('file_count')) !!},
                backgroundColor: [
                    'rgba(255, 212, 29, 0.8)',
                    'rgba(255, 162, 64, 0.8)',
                    'rgba(215, 53, 53, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        color: '#64748b',
                        font: {
                            size: window.innerWidth < 480 ? 10 : 12
                        }
                    }
                }
            },
            scales: {
                r: {
                    ticks: {
                        display: false
                    },
                    grid: {
                        color: 'rgba(226, 232, 240, 0.5)'
                    }
                }
            }
        }
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            monthlyUploadsChart.resize();
            fileTypesChart.resize();
            categoryChart.resize();
        }, 250);
    });
});
</script>
@endpush