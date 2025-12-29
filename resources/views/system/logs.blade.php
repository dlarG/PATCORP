@extends('layouts.admin')

@section('title', 'System and File Logs')
@section('breadcrumb-section', 'System Overview')
@section('breadcrumb-current', 'System and File Logs')
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #FFD41D;
        --secondary: #FFA240;
        --accent: #D73535;
        --dark: #1e293b;
        --light: #f8fafc;
        --gray: #64748b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
    }

    .logs-header {
        background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
        color: #1a202c;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }


    .logs-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .logs-header p {
        font-size: 16px;
        opacity: 0.8;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 25px 0;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
        color: white;
    }

    .stat-icon.system { background: var(--info); }
    .stat-icon.files { background: var(--success); }
    .stat-icon.today { background: var(--warning); }
    .stat-icon.total { background: var(--accent); }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--gray);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .logs-content {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .tabs-container {
        background: var(--light);
        border-bottom: 2px solid #e2e8f0;
        padding: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tabs {
        display: flex;
    }

    .tab-btn {
        padding: 20px 30px;
        background: none;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tab-btn.active {
        color: var(--primary);
        background: white;
        border-radius: 15px 15px 0 0;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary);
    }

    .tab-actions {
        padding: 20px 30px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary);
        color: var(--dark);
    }

    .btn-secondary {
        background: var(--gray);
        color: white;
    }

    .btn-danger {
        background: var(--danger);
        color: white;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .filters-section {
        padding: 25px 30px;
        background: var(--light);
        border-bottom: 1px solid #e2e8f0;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .filter-group, .filter-group.file-filter, .filter-group.system-filter {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group, .filter-group.file-filter, .filter-group.system-filter label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: var(--primary);
        outline: none;
    }

    .tab-content {
        padding: 30px;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .logs-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .logs-table th,
    .logs-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .logs-table th {
        background: var(--light);
        font-weight: 700;
        color: var(--dark);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .logs-table tr:hover {
        background: rgba(255, 212, 29, 0.05);
    }

    .log-time {
        font-size: 0.85rem;
        color: var(--gray);
    }

    .log-user {
        font-weight: 600;
        color: var(--dark);
    }

    .log-action {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .action-create { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .action-update_driver { background: rgba(59, 130, 246, 0.1); color: var(--info); }
    .action-toggle_payment_status { background: rgba(246, 240, 59, 0.1); color: #FFA240; }
    .action-delete_driver { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .action-view { background: rgba(107, 114, 128, 0.1); color: var(--gray); }
    .action-download { background: rgba(255, 162, 64, 0.1); color: var(--secondary); }
    .action-login { background: rgba(34, 197, 94, 0.1); color: var(--success); }
    .action-register { background: rgba(34, 197, 94, 0.1); color: var(--success); }
    .action-logout { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

    .log-module {
        font-weight: 600;
        color: var(--secondary);
        text-transform: capitalize;
    }

    .log-ip {
        font-family: 'Courier New', monospace;
        background: var(--light);
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
    padding: 20px 0;
}

.pagination-wrapper .pagination {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.pagination-wrapper .page-item {
    margin: 0;
}

.pagination-wrapper .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 45px;
    height: 45px;
    padding: 0 15px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    color: var(--gray);
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.pagination-wrapper .page-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 212, 29, 0.3), transparent);
    transition: left 0.5s;
}

.pagination-wrapper .page-link:hover::before {
    left: 100%;
}

.pagination-wrapper .page-link:hover {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-color: var(--primary);
    color: var(--dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 212, 29, 0.3);
}

.pagination-wrapper .page-item.active .page-link {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-color: var(--primary);
    color: var(--dark);
    font-weight: 700;
    box-shadow: 0 5px 15px rgba(255, 212, 29, 0.4);
}

.pagination-wrapper .page-item.disabled .page-link {
    background: #f1f5f9;
    border-color: #e2e8f0;
    color: #cbd5e0;
    cursor: not-allowed;
    opacity: 0.6;
}

.pagination-wrapper .page-item.disabled .page-link:hover {
    background: #f1f5f9;
    transform: none;
    box-shadow: none;
}

/* Special styling for prev/next buttons */
.pagination-wrapper .page-item:first-child .page-link,
.pagination-wrapper .page-item:last-child .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    color: white;
    font-weight: 700;
}

.pagination-wrapper .page-item:first-child .page-link:hover,
.pagination-wrapper .page-item:last-child .page-link:hover {
    background: linear-gradient(135deg, #764ba2, #667eea);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.pagination-wrapper .page-item:first-child.disabled .page-link,
.pagination-wrapper .page-item:last-child.disabled .page-link {
    background: #f1f5f9;
    border-color: #e2e8f0;
    color: #cbd5e0;
}

/* Pagination info */
.pagination-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: var(--light);
    border-radius: 12px;
    font-size: 14px;
    color: var(--gray);
}

.pagination-info .results-count {
    font-weight: 600;
    color: var(--dark);
}

.pagination-info .page-size-selector {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pagination-info .page-size-selector select {
    padding: 8px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    font-size: 14px;
    color: var(--dark);
}

.pagination-info .page-size-selector select:focus {
    border-color: var(--primary);
    outline: none;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .pagination-wrapper .pagination {
        gap: 4px;
    }
    
    .pagination-wrapper .page-link {
        min-width: 38px;
        height: 38px;
        padding: 0 10px;
        font-size: 13px;
    }
    
    .pagination-info {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    /* Hide some page numbers on mobile */
    .pagination-wrapper .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
        display: none;
    }
}

/* Loading animation for pagination */
.pagination-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 20px;
    color: var(--primary);
    font-weight: 600;
}

.pagination-loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Enhanced pagination with jump to page */
.pagination-controls {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
}

.jump-to-page {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: white;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    font-size: 14px;
}

.jump-to-page input {
    width: 60px;
    padding: 6px 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    text-align: center;
    font-weight: 600;
}

.jump-to-page button {
    padding: 6px 15px;
    background: var(--primary);
    border: none;
    border-radius: 6px;
    color: var(--dark);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.jump-to-page button:hover {
    background: var(--secondary);
    transform: translateY(-1px);
}

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .clear-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .clear-modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    .modal-content h3 {
        color: var(--danger);
        margin-bottom: 15px;
    }

    .days-input {
        width: 80px;
        text-align: center;
        margin: 0 10px;
    }

    @media (max-width: 768px) {
        .logs-container {
            padding: 15px;
        }
        
        .tabs-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .tab-actions {
            justify-content: center;
            padding: 15px;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .logs-table {
            font-size: 0.85rem;
        }
        
        .logs-table th,
        .logs-table td {
            padding: 10px 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="logs-container">
    <!-- Header -->
    <div class="logs-header">
        <h1>
            System Logs
        </h1>
        <p>Monitor system activities and file access logs</p>        
    </div>
    <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon system">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['system_logs_count']) }}</div>
                <div class="stat-label">System Logs</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon files">
                    <i class="fas fa-file"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['file_logs_count']) }}</div>
                <div class="stat-label">File Logs</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['today_system_logs']) }}</div>
                <div class="stat-label">Today System</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon today">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-number">{{ number_format($stats['today_file_logs']) }}</div>
                <div class="stat-label">Today Files</div>
            </div>
        </div>

    <!-- Logs Content -->
    <div class="logs-content">
        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tabs">
                <button class="tab-btn {{ $activeTab === 'system' ? 'active' : '' }}" onclick="switchTab('system')">
                    <i class="fas fa-cog"></i>
                    System Logs
                </button>
                <button class="tab-btn {{ $activeTab === 'file' ? 'active' : '' }}" onclick="switchTab('file')">
                    <i class="fas fa-file"></i>
                    File Access Logs
                </button>
            </div>
            
            <div class="tab-actions">
                <button class="btn btn-primary" onclick="exportLogs()">
                    <i class="fas fa-download"></i>
                    Export
                </button>
                <button class="btn btn-danger" onclick="openClearModal()">
                    <i class="fas fa-trash"></i>
                    Clear Old Logs
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('logs.index') }}" id="filterForm">
                <input type="hidden" name="tab" value="{{ $activeTab }}" id="tabInput">
                
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label>User</label>
                        <select name="user_id">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group system-filter">
                        <label>Module</label>
                        <select name="module">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                                    {{ ucfirst($module) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group file-filter">
                        <label>Action</label>
                        <select name="action">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <div style="display: flex; gap: 10px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i>
                                Filter
                            </button>
                            <a href="{{ route('logs.index') }}?tab={{ $activeTab }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- System Logs Tab -->
        <div class="tab-content {{ $activeTab === 'system' ? 'active' : '' }}" id="system-tab">
            @if($systemLogs->count() > 0)
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($systemLogs as $log)
                            <tr>
                                <td class="log-time">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="log-user">
                                    {{ $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'System' }}
                                </td>
                                <td>
                                    <span class="log-action action-{{ strtolower($log->action) }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="log-module">{{ $log->module }}</td>
                                <td>{{ $log->description }}</td>
                                <td class="log-ip">{{ $log->ip_address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="pagination-controls">
                    {{-- <div class="pagination-wrapper">
                        {{ $systemLogs->appends(request()->query())->links('custom.pagination') }}
                    </div> --}}
                    
                    <div class="pagination-info">
                        <div class="results-count">
                            <i class="fas fa-info-circle"></i>
                            Showing {{ $systemLogs->firstItem() ?? 0 }} to {{ $systemLogs->lastItem() ?? 0 }} 
                            of {{ $systemLogs->total() }} system logs
                        </div>
                        
                        <div class="page-size-selector">
                            <label>Show:</label>
                            <select onchange="changePageSize(this.value, 'system')">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                            </select>
                        </div>
                    </div>
                    
                    @if($systemLogs->lastPage() > 1)
                        <div class="jump-to-page">
                            <label>Jump to page:</label>
                            <input type="number" id="jumpPageSystem" min="1" max="{{ $systemLogs->lastPage() }}" 
                                value="{{ $systemLogs->currentPage() }}">
                            <button onclick="jumpToPage('system')">Go</button>
                        </div>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No system logs found</h3>
                    <p>No system logs match your current filters.</p>
                </div>
            @endif
        </div>

        <!-- File Logs Tab -->
        <div class="tab-content {{ $activeTab === 'file' ? 'active' : '' }}" id="file-tab">
            @if($fileLogs->count() > 0)
                <table class="logs-table">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>File</th>
                            <th>Action</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fileLogs as $log)
                            <tr>
                                <td class="log-time">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="log-user">
                                    {{ $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'Unknown' }}
                                </td>
                                <td>
                                    @if($log->file)
                                        <strong>{{ $log->file->original_filename }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $log->file->category->category_name ?? 'Uncategorized' }}</small>
                                    @else
                                        <em class="text-muted">Deleted File</em>
                                    @endif
                                </td>
                                <td>
                                    <span class="log-action action-{{ strtolower($log->action) }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="log-ip">{{ $log->ip_address }}</td>
                                <td>
                                    <small>{{ Str::limit($log->user_agent, 50) }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="pagination-controls">
                    {{-- <div class="pagination-wrapper">
                        {{ $fileLogs->appends(request()->query())->links('custom.pagination') }}
                    </div> --}}
                    
                    <div class="pagination-info">
                        <div class="results-count">
                            <i class="fas fa-info-circle"></i>
                            Showing {{ $fileLogs->firstItem() ?? 0 }} to {{ $fileLogs->lastItem() ?? 0 }} 
                            of {{ $fileLogs->total() }} file access logs
                        </div>
                        
                        <div class="page-size-selector">
                            <label>Show:</label>
                            <select onchange="changePageSize(this.value, 'file')">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                            </select>
                        </div>
                    </div>
                    
                    @if($fileLogs->lastPage() > 1)
                        <div class="jump-to-page">
                            <label>Jump to page:</label>
                            <input type="number" id="jumpPageFile" min="1" max="{{ $fileLogs->lastPage() }}" 
                                value="{{ $fileLogs->currentPage() }}">
                            <button onclick="jumpToPage('file')">Go</button>
                        </div>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No file access logs found</h3>
                    <p>No file access logs match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="clear-modal" id="clearModal">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle"></i> Clear Old Logs</h3>
        <p>This will permanently delete logs older than the specified number of days.</p>
        
        <div style="margin: 20px 0;">
            <label>Delete logs older than:</label>
            <input type="number" id="daysInput" class="days-input" value="30" min="1" max="365">
            <label>days</label>
        </div>
        
        <div style="display: flex; gap: 15px; justify-content: center; margin-top: 25px;">
            <button class="btn btn-danger" onclick="clearLogs()">
                <i class="fas fa-trash"></i>
                Clear Logs
            </button>
            <button class="btn btn-secondary" onclick="closeClearModal()">
                Cancel
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentTab = '{{ $activeTab }}';


function changePageSize(perPage, type) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('tab', type);
    
    // Reset to page 1 when changing page size
    url.searchParams.delete('page');
    url.searchParams.delete('system_page');
    url.searchParams.delete('file_page');
    
    window.location.href = url.toString();
}

function jumpToPage(type) {
    const inputId = type === 'system' ? 'jumpPageSystem' : 'jumpPageFile';
    const pageNumber = document.getElementById(inputId).value;
    
    if (!pageNumber || pageNumber < 1) {
        alert('Please enter a valid page number');
        return;
    }
    
    const url = new URL(window.location);
    url.searchParams.set('tab', type);
    
    if (type === 'system') {
        url.searchParams.set('system_page', pageNumber);
    } else {
        url.searchParams.set('file_page', pageNumber);
    }
    
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && 
                !this.parentElement.classList.contains('active')) {
                
                // Show loading state
                const wrapper = this.closest('.pagination-wrapper');
                wrapper.innerHTML = `
                    <div class="pagination-loading">
                        <i class="fas fa-spinner"></i>
                        Loading...
                    </div>
                `;
            }
        });
    });
});


function switchTab(tab) {
    currentTab = tab;
    
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('active');
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    document.getElementById(`${tab}-tab`).classList.add('active');
    
    // Update hidden form input
    document.getElementById('tabInput').value = tab;
    
    
    // Update URL
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
}

function exportLogs() {
    const params = new URLSearchParams(window.location.search);
    params.set('type', currentTab);
    
    window.location.href = '{{ route("logs.export") }}?' + params.toString();
}

function openClearModal() {
    document.getElementById('clearModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeClearModal() {
    document.getElementById('clearModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

async function clearLogs() {
    const days = document.getElementById('daysInput').value;
    
    if (!days || days < 1) {
        alert('Please enter a valid number of days');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete all ${currentTab} logs older than ${days} days? This action cannot be undone.`)) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("logs.clear") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                type: currentTab,
                days: parseInt(days)
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            closeClearModal();
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Failed to clear logs: ' + error.message);
    }
}

// Auto-submit form when filters change
document.querySelectorAll('#filterForm select, #filterForm input[type="date"]').forEach(input => {
    input.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('clearModal');
    if (e.target === modal) {
        closeClearModal();
    }
});
</script>
@endpush