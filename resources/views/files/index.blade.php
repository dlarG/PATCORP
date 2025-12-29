@extends('layouts.admin')
@php
function getFileIcon($extension) {
    $icons = [
        'pdf' => 'fa-file-pdf',
        'doc' => 'fa-file-word',
        'docx' => 'fa-file-word',
        'xls' => 'fa-file-excel',
        'xlsx' => 'fa-file-excel',
        'ppt' => 'fa-file-powerpoint',
        'pptx' => 'fa-file-powerpoint',
        'txt' => 'fa-file-alt',
        'jpg' => 'fa-file-image',
        'jpeg' => 'fa-file-image',
        'png' => 'fa-file-image',
        'gif' => 'fa-file-image'
    ];
    return $icons[strtolower($extension)] ?? 'fa-file';
}

function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    
    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
@endphp
@section('title', 'File Management')
@section('breadcrumb-section', 'Management')
@section('breadcrumb-current', 'Files')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #FFD41D;
        --primary-dark: #E6BF1A;
        --secondary: #FFA240;
        --secondary-dark: #E68C36;
        --tertiary: #D73535;
        --tertiary-dark: #BF3030;
        --dark: #1a202c;
        --light: #f8fafc;
        --gray: #718096;
        --gray-light: #e2e8f0;
        --success: #38a169;
        --info: #3182ce;
        --shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.25);
        --shadow-sm: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
    }

    .files-container {
        background: linear-gradient(135deg, var(--light) 0%, #edf2f7 100%);
        min-height: 100vh;
    }

    .files-header {
        background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
        color: #1a202c;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .files-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255, 212, 29, 0.1));
    }

    .files-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .files-header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark);
        font-size: 26px;
        box-shadow: var(--shadow-sm);
    }

    .files-header p {
        font-size: 16px;
        opacity: 0.8;
    }

    .files-actions {
        background: white;
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .upload-btn {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: var(--dark);
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        text-align: center;
        justify-content: center;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 5px 20px rgba(255, 212, 29, 0.3);
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .upload-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .upload-btn:hover::before {
        left: 100%;
    }

    .upload-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 212, 29, 0.4);
    }

    .upload-btn:active {
        transform: translateY(-1px);
    }

    .search-container {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-input {
        width: 100%;
        padding: 14px 20px 14px 50px;
        border: 2px solid var(--gray-light);
        border-radius: 12px;
        font-size: 1rem;
        background: var(--light);
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 212, 29, 0.1);
        background: white;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        font-size: 1.2rem;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
        border-top: 4px solid;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }

    .stat-card.total {
        border-color: var(--primary);
    }

    .stat-card.pdf {
        border-color: #e53e3e;
    }

    .stat-card.doc {
        border-color: #3182ce;
    }

    .stat-card.other {
        border-color: var(--gray);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--dark);
    }

    .stat-icon.total { background: linear-gradient(135deg, var(--primary), var(--secondary)); }
    .stat-icon.pdf { background: linear-gradient(135deg, #fc8181, #e53e3e); color: white; }
    .stat-icon.doc { background: linear-gradient(135deg, #90cdf4, #3182ce); color: white; }
    .stat-icon.other { background: linear-gradient(135deg, var(--gray-light), var(--gray)); color: white; }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 5px;
    }

    .stat-label {
        color: var(--gray);
        font-size: 0.95rem;
        font-weight: 600;
    }

    .files-grid-container {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: var(--shadow-sm);
    }

    .files-grid-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--gray-light);
    }

    .files-grid-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        border: 2px solid var(--gray-light);
        background: white;
        border-radius: 8px;
        font-weight: 600;
        color: var(--gray);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn:hover {
        border-color: var(--primary);
        color: var(--dark);
    }

    .filter-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: var(--dark);
    }

    .files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
    }

    .file-card {
        background: var(--light);
        border-radius: 15px;
        padding: 25px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .file-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 5px 0 0 5px;
    }

    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
        background: white;
        border-color: var(--primary);
    }

    .file-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 15px;
    }

    .file-icon {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    .file-icon.pdf { background: linear-gradient(135deg, #fc8181, #e53e3e); }
    .file-icon.doc, .file-icon.docx { background: linear-gradient(135deg, #90cdf4, #3182ce); }
    .file-icon.xls, .file-icon.xlsx { background: linear-gradient(135deg, #9ae6b4, #38a169); }
    .file-icon.ppt, .file-icon.pptx { background: linear-gradient(135deg, #fbd38d, #d69e2e); }
    .file-icon.txt { background: linear-gradient(135deg, #a0aec0, #718096); }
    .file-icon.default { background: linear-gradient(135deg, var(--secondary), var(--tertiary)); }

    .file-info {
        flex: 1;
        min-width: 0;
    }

    .file-name {
        font-weight: 700;
        color: var(--dark);
        font-size: 1.1rem;
        margin-bottom: 5px;
        word-break: break-word;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .file-category {
        display: inline-block;
        background: rgba(255, 212, 29, 0.1);
        color: var(--dark);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 5px;
        border: 1px solid rgba(255, 212, 29, 0.3);
    }

    .file-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--gray-light);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: var(--gray);
    }

    .meta-item i {
        color: var(--primary);
        font-size: 0.9rem;
    }

    .file-description {
        color: var(--gray);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid var(--gray-light);
    }

    .file-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--gray-light);
    }

    .file-action-btn {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .action-view {
        background: rgba(255, 212, 29, 0.1);
        color: var(--dark);
        border: 2px solid transparent;
    }

    .action-view:hover {
        background: var(--primary);
        transform: translateY(-2px);
    }

    .action-download {
        background: rgba(56, 161, 105, 0.1);
        color: #38a169;
        border: 2px solid transparent;
    }

    .action-download:hover {
        background: #38a169;
        color: white;
        transform: translateY(-2px);
    }

    .action-delete {
        background: rgba(215, 53, 53, 0.1);
        color: var(--tertiary);
        border: 2px solid transparent;
    }

    .action-delete:hover {
        background: var(--tertiary);
        color: white;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--gray);
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--gray-light), var(--gray));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
        margin: 0 auto 25px;
        box-shadow: var(--shadow-sm);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: var(--dark);
    }

    .empty-state p {
        max-width: 500px;
        margin: 0 auto 25px;
        line-height: 1.6;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(26, 32, 44, 0.7);
        backdrop-filter: blur(5px);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.show {
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
    }

    .modal {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-lg);
        border: 2px solid var(--primary);
    }

    .modal-overlay.show .modal {
        transform: scale(1);
        opacity: 1;
    }

    .modal-header {
        padding: 25px 30px 20px;
        border-bottom: 2px solid var(--gray-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 18px 18px 0 0;
        color: var(--dark);
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: var(--dark);
        transition: transform 0.3s ease;
    }

    .close-btn:hover {
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--dark);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid var(--gray-light);
        border-radius: 12px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
        background: var(--light);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255, 212, 29, 0.1);
        background: white;
    }

    .form-group textarea {
        height: 100px;
        resize: vertical;
    }

    .file-drop-area {
        border: 3px dashed var(--gray-light);
        border-radius: 15px;
        padding: 50px 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: var(--light);
        cursor: pointer;
    }

    .file-drop-area:hover {
        border-color: var(--primary);
        background: rgba(255, 212, 29, 0.05);
    }

    .file-drop-area.dragover {
        border-color: var(--primary);
        background: rgba(255, 212, 29, 0.1);
    }

    .file-drop-area i {
        font-size: 48px;
        color: var(--primary);
        margin-bottom: 15px;
    }

    .file-drop-area p {
        margin: 0;
        font-size: 1.1rem;
    }

    .file-drop-area .small {
        font-size: 0.9rem;
        color: var(--gray);
        margin-top: 5px;
    }

    .progress-container {
        margin-top: 20px;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: var(--gray-light);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        width: 0%;
        transition: width 0.3s ease;
        border-radius: 4px;
    }

    .progress-text {
        text-align: center;
        margin-top: 10px;
        font-size: 0.9rem;
        color: var(--gray);
        font-weight: 600;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .form-actions .btn {
        flex: 1;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-cancel {
        background: var(--gray-light);
        color: var(--dark);
    }

    .btn-cancel:hover {
        background: #cbd5e0;
        transform: translateY(-2px);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: var(--dark);
        box-shadow: 0 5px 20px rgba(255, 212, 29, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 212, 29, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    /* File Details Modal */
    .file-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-item.full {
        grid-column: 1 / -1;
    }

    .detail-label {
        font-weight: 600;
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-value {
        color: var(--dark);
        font-size: 1rem;
        word-break: break-word;
    }

    .detail-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 5px;
    }

    .detail-tag {
        background: rgba(255, 212, 29, 0.1);
        color: var(--dark);
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid rgba(255, 212, 29, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .files-grid {
            grid-template-columns: 1fr;
        }

        .files-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .search-container {
            max-width: 100%;
        }

        .file-details {
            grid-template-columns: 1fr;
        }

        .stats-cards {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .files-header h1 {
            font-size: 1.8rem;
        }

        .files-header-icon {
            width: 50px;
            height: 50px;
            font-size: 22px;
        }

        .upload-btn {
            padding: 12px 20px;
            font-size: 0.9rem;
        }

        .filter-buttons {
            flex-direction: column;
        }

        .filter-btn {
            width: 100%;
        }
    }

    /* Animation for file cards */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .file-card {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .file-card:nth-child(2) { animation-delay: 0.1s; }
    .file-card:nth-child(3) { animation-delay: 0.2s; }
    .file-card:nth-child(4) { animation-delay: 0.3s; }
    .file-card:nth-child(5) { animation-delay: 0.4s; }
    .file-card:nth-child(6) { animation-delay: 0.5s; }
    .file-card:nth-child(7) { animation-delay: 0.6s; }
    .file-card:nth-child(8) { animation-delay: 0.7s; }
</style>
@endpush

@section('content')
<div class="files-container">
    <!-- Header -->
    <div class="files-header">
        <div class="d-flex align-items-center">
            <div>
                <h1>File Management System</h1>
                <p>Upload, organize, and manage all your files in one place</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card total">
            <div class="stat-icon total">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $files->count() }}</div>
                <div class="stat-label">Total Files</div>
            </div>
        </div>
        
        <div class="stat-card pdf">
            <div class="stat-icon pdf">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $files->where('file_type', 'application/pdf')->count() }}</div>
                <div class="stat-label">PDF Files</div>
            </div>
        </div>
        
        <div class="stat-card doc">
            <div class="stat-icon doc">
                <i class="fas fa-file-word"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $files->whereIn('file_type', ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->count() }}</div>
                <div class="stat-label">Word Documents</div>
            </div>
        </div>
        
        <div class="stat-card other">
            <div class="stat-icon other">
                <i class="fas fa-file"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $files->count() - $files->where('file_type', 'application/pdf')->count() - $files->whereIn('file_type', ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->count() }}</div>
                <div class="stat-label">Other Files</div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="files-actions">
        <button class="upload-btn" onclick="openUploadModal()">
            <i class="fas fa-cloud-upload-alt"></i>
            Upload New File
        </button>
        
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search files by name or category...">
        </div>
    </div>

    <!-- Files Grid Container -->
    <div class="files-grid-container">
        <div class="files-grid-header">
            <h3><i class="fas fa-folder"></i> All Files</h3>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All Files</button>
                <button class="filter-btn" data-filter="pdf">PDF</button>
                <button class="filter-btn" data-filter="doc">Documents</button>
                <button class="filter-btn" data-filter="excel">Excel</button>
                <button class="filter-btn" data-filter="other">Other</button>
            </div>
        </div>

        <!-- Files Grid -->
            @if($files->count() > 0)
                @foreach($files as $file)
                    @php
                        $extension = strtolower(pathinfo($file->original_filename, PATHINFO_EXTENSION));
                        $fileType = 'other';
                        if (in_array($extension, ['pdf'])) $fileType = 'pdf';
                        elseif (in_array($extension, ['doc', 'docx', 'txt'])) $fileType = 'doc';
                        elseif (in_array($extension, ['xls', 'xlsx', 'csv'])) $fileType = 'excel';
                    @endphp
                    
                    <div class="file-card" data-file-type="{{ $fileType }}" 
                         data-filename="{{ strtolower($file->original_filename) }}"
                         data-category="{{ strtolower($file->category->category_name ?? 'uncategorized') }}">
                        <div class="file-header">
                            <div class="file-icon {{ $extension }}">
                                <i class="fas {{ getFileIcon($extension) }}"></i>
                            </div>
                            <div class="file-info">
                                <div class="file-name">
                                    <span>{{ $file->original_filename }}</span>
                                    @if($file->is_public)
                                        <span class="badge badge-success"><i class="fas fa-globe"></i> Public</span>
                                    @endif
                                </div>
                                @if($file->category)
                                    <span class="file-category">{{ $file->category->category_name }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="file-meta">
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>{{ $file->uploadedBy->first_name ?? 'Unknown' }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $file->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-hdd"></i>
                                <span>{{ formatFileSize($file->file_size) }}</span>
                            </div>
                        </div>
                        
                        @if($file->description)
                            <div class="file-description">
                                {{ Str::limit($file->description, 120) }}
                            </div>
                        @endif
                        
                        <div class="file-actions">
                            <button class="file-action-btn action-view" onclick="viewFile({{ $file->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="file-action-btn action-download" onclick="downloadFile({{ $file->id }})">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <button class="file-action-btn action-delete" onclick="deleteFile({{ $file->id }}, '{{ $file->original_filename }}')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3>No Files Found</h3>
                    <p>Upload your first file to get started with file management. Click the upload button above to begin.</p>
                </div>
            @endif
    </div>
</div>

<!-- Upload Modal -->
<div class="modal-overlay" id="uploadModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-cloud-upload-alt"></i> Upload New File</h3>
            <button class="close-btn" onclick="closeUploadModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label><i class="fas fa-file"></i> Select File</label>
                    <div class="file-drop-area" id="fileDropArea">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drop your file here or <strong>click to browse</strong></p>
                        <p class="small">Supported: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT (Max 10MB)</p>
                        <input type="file" id="fileInput" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif" style="display: none;">
                    </div>
                    
                    <div class="progress-container" id="progressContainer" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="progress-text" id="progressText">0%</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Category</label>
                    <select name="category_id" id="categorySelect" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                        <option value="new">+ Create New Category</option>
                    </select>
                    <div id="newCategoryField" style="display: none; margin-top: 10px;">
                        <input type="text" name="new_category" placeholder="Enter new category name" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Assign to Driver (Optional)</label>
                    <select name="driver_id" id="driverSelect">
                        <option value="">Not assigned</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->user->first_name }} {{ $driver->user->last_name }} ({{ $driver->driver_id }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-align-left"></i> Description (Optional)</label>
                    <textarea name="description" placeholder="Describe what this file contains..."></textarea>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-tags"></i> Tags (Optional)</label>
                    <input type="text" name="tags" placeholder="important, report, monthly (separate with commas)">
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_public" id="isPublic" class="form-check-input">
                        <label for="isPublic" class="form-check-label">
                            <i class="fas fa-globe"></i> Make this file publicly accessible
                        </label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeUploadModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-submit" id="uploadBtn">
                        <i class="fas fa-cloud-upload-alt"></i> Upload File
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- File Details Modal -->
<div class="modal-overlay" id="fileDetailsModal">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-file-alt"></i> File Details</h3>
            <button class="close-btn" onclick="closeFileDetailsModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <div id="fileDetailsContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variable to track if file drop is already setup
let fileDropSetup = false;

// Format file size function
function formatFileSize(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Modal Functions
function openUploadModal() {
    document.getElementById('uploadModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Setup file drop only once
    if (!fileDropSetup) {
        setupFileDrop();
        fileDropSetup = true;
    }
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    resetUploadForm();
}

function resetUploadForm() {
    const form = document.getElementById('uploadForm');
    form.reset();
    
    document.getElementById('newCategoryField').style.display = 'none';
    document.getElementById('progressContainer').style.display = 'none';
    document.getElementById('progressFill').style.width = '0%';
    document.getElementById('progressText').textContent = '0%';
    
    // Reset file drop area
    const fileDropArea = document.getElementById('fileDropArea');
    fileDropArea.className = 'file-drop-area';
    fileDropArea.innerHTML = `
        <i class="fas fa-cloud-upload-alt"></i>
        <p>Drop your file here or <strong>click to browse</strong></p>
        <p class="small">Supported: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF (Max 10MB)</p>
    `;
    
    // Ensure there's a file input
    let fileInput = fileDropArea.querySelector('input[type="file"]');
    if (!fileInput) {
        fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.name = 'file';
        fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif';
        fileInput.style.display = 'none';
        fileInput.id = 'fileInput';
        fileInput.required = true;
        fileDropArea.appendChild(fileInput);
    } else {
        // Clear the file input value
        fileInput.value = '';
    }
    
    // Re-setup event listeners
    setupFileDropListeners(fileDropArea, fileInput);
}

// File Drop Area Setup - Main function
function setupFileDrop() {
    const fileDropArea = document.getElementById('fileDropArea');
    
    if (!fileDropArea) {
        console.error('File drop area not found!');
        return;
    }
    
    // Remove any existing file input
    const existingInput = fileDropArea.querySelector('input[type="file"]');
    if (existingInput) {
        existingInput.remove();
    }
    
    // Create fresh file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'file';
    fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif';
    fileInput.style.display = 'none';
    fileInput.id = 'fileInput';
    fileInput.required = true;
    
    fileDropArea.appendChild(fileInput);
    
    // Setup event listeners
    setupFileDropListeners(fileDropArea, fileInput);
}

// Setup file drop listeners separately
function setupFileDropListeners(fileDropArea, fileInput) {
    // Remove all existing event listeners by removing and re-adding events
    // We'll do this by creating a fresh set of listeners
    
    // Click on drop area to trigger file input
    fileDropArea.addEventListener('click', function fileDropClickHandler(e) {
        if (e.target !== fileInput && !e.target.classList.contains('change-file')) {
            fileInput.click();
        }
    });
    
    // Handle drag events
    const handleDragOver = function(e) {
        e.preventDefault();
        fileDropArea.classList.add('dragover');
    };
    
    const handleDragLeave = function(e) {
        e.preventDefault();
        fileDropArea.classList.remove('dragover');
    };
    
    const handleDrop = function(e) {
        e.preventDefault();
        fileDropArea.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect(e.dataTransfer.files[0]);
        }
    };
    
    fileDropArea.addEventListener('dragover', handleDragOver);
    fileDropArea.addEventListener('dragenter', handleDragOver);
    fileDropArea.addEventListener('dragleave', handleDragLeave);
    fileDropArea.addEventListener('dragend', handleDragLeave);
    fileDropArea.addEventListener('drop', handleDrop);
    
    // Handle file input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length) {
            handleFileSelect(e.target.files[0]);
        }
    });
}


function handleFileSelect(file) {
    const fileDropArea = document.getElementById('fileDropArea');
    
    // Update display
    fileDropArea.innerHTML = `
        <div class="file-preview">
            <i class="fas fa-file-alt"></i>
            <div class="file-info">
                <p class="file-name"><strong>${file.name}</strong></p>
                <p class="file-size">${formatFileSize(file.size)}</p>
            </div>
        </div>
        <p class="small change-file" style="color: var(--primary); cursor: pointer; margin-top: 10px;">
            <i class="fas fa-exchange-alt"></i> Click to change file
        </p>
    `;
    
    // Add the file input back
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'file';
    fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif';
    fileInput.style.display = 'none';
    fileInput.id = 'fileInput';
    fileInput.required = true;
    
    // Set the selected file
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput.files = dataTransfer.files;
    
    fileDropArea.appendChild(fileInput);
    
    // Add click event to "change file" text
    const changeFileText = fileDropArea.querySelector('.change-file');
    if (changeFileText) {
        changeFileText.addEventListener('click', function(e) {
            e.stopPropagation();
            resetFileSelection();
        });
    }
    
    // Setup new listeners
    setupFileDropListeners(fileDropArea, fileInput);
}

function resetFileSelection() {
    const fileDropArea = document.getElementById('fileDropArea');
    
    // Reset to initial state
    fileDropArea.className = 'file-drop-area';
    fileDropArea.innerHTML = `
        <i class="fas fa-cloud-upload-alt"></i>
        <p>Drop your file here or <strong>click to browse</strong></p>
        <p class="small">Supported: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, PNG, GIF (Max 10MB)</p>
    `;
    
    // Add fresh file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.name = 'file';
    fileInput.accept = '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif';
    fileInput.style.display = 'none';
    fileInput.id = 'fileInput';
    fileInput.required = true;
    
    fileDropArea.appendChild(fileInput);
    
    // Setup listeners again
    setupFileDropListeners(fileDropArea, fileInput);
}
// Category Selection
document.getElementById('categorySelect').addEventListener('change', function() {
    if (this.value === 'new') {
        document.getElementById('newCategoryField').style.display = 'block';
    } else {
        document.getElementById('newCategoryField').style.display = 'none';
    }
});

// Upload Form Submission
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Get the file input
    const fileInput = document.querySelector('#fileDropArea input[type="file"]');
    
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        showNotification('Please select a file to upload', 'error');
        return;
    }
    
    const file = fileInput.files[0];
    console.log('Uploading file:', file.name, file.size);
    
    // Validate file size
    if (file.size > 10 * 1024 * 1024) { // 10MB
        showNotification('File size must be less than 10MB', 'error');
        return;
    }
    
    const formData = new FormData(this);
    
    // Ensure file is properly appended
    formData.set('file', file);
    
    // Debug: Log form data
    for (let [key, value] of formData.entries()) {
        console.log(key + ':', value instanceof File ? value.name + ' (' + value.size + ' bytes)' : value);
    }
    
    const uploadBtn = document.getElementById('uploadBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    
    // Show progress and disable button
    progressContainer.style.display = 'block';
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    
    try {
        const xhr = new XMLHttpRequest();
        
        // Progress tracking
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressFill.style.width = percentComplete + '%';
                progressText.textContent = Math.round(percentComplete) + '%';
            }
        });
        
        xhr.onload = function() {
            console.log('Upload response status:', xhr.status);
            console.log('Response:', xhr.responseText);
            
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        showNotification('File uploaded successfully!', 'success');
                        closeUploadModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        console.error('Server error:', response);
                        showNotification(response.message || 'Upload failed', 'error');
                        
                        if (response.errors) {
                            Object.entries(response.errors).forEach(([field, messages]) => {
                                showNotification(`${field}: ${messages.join(', ')}`, 'error');
                            });
                        }
                    }
                } catch (error) {
                    console.error('JSON Parse Error:', error);
                    showNotification('Invalid server response', 'error');
                }
            } else {
                console.error('HTTP Error:', xhr.status, xhr.statusText);
                showNotification('Upload failed. Server error ' + xhr.status, 'error');
            }
        };
        
        xhr.onerror = function() {
            console.error('Network error during upload');
            showNotification('Upload failed. Please check your connection.', 'error');
        };
        
        xhr.onloadend = function() {
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Upload File';
            progressContainer.style.display = 'none';
        };
        
        xhr.open('POST', '{{ route("files.store") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        xhr.setRequestHeader('Accept', 'application/json');
        
        console.log('Sending upload request...');
        xhr.send(formData);
        
    } catch (error) {
        console.error('Upload error:', error);
        showNotification('Upload failed: ' + error.message, 'error');
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Upload File';
        progressContainer.style.display = 'none';
    }
});

// File Operations (keep your existing functions for download, delete, view)
async function downloadFile(fileId) {
    try {
        window.open(`/files/${fileId}/download`, '_blank');
        showNotification('Download started...', 'success');
    } catch (error) {
        console.error('Download error:', error);
        showNotification('Failed to download file', 'error');
    }
}

async function deleteFile(fileId, filename) {
    if (!confirm(`Are you sure you want to delete "${filename}"?\nThis action cannot be undone.`)) {
        return;
    }
    
    try {
        const response = await fetch(`/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('File deleted successfully!', 'success');
            // Find and remove the file card
            const fileCard = document.querySelector(`.file-card[onclick*="${fileId}"]`);
            if (fileCard) {
                fileCard.remove();
            }
            
            // Check if no files left
            const fileCards = document.querySelectorAll('.file-card');
            if (fileCards.length === 0) {
                window.location.reload();
            }
        } else {
            showNotification('Failed to delete file: ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Delete error:', error);
        showNotification('Failed to delete file', 'error');
    }
}

async function viewFile(fileId) {
    try {
        const response = await fetch(`/files/${fileId}`, {
            headers: {
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showFileDetails(data.file);
        } else {
            showNotification('Failed to load file details', 'error');
        }
    } catch (error) {
        console.error('View error:', error);
        showNotification('Failed to load file details', 'error');
    }
}

function showFileDetails(file) {
    const tags = file.tags ? file.tags.split(',').map(tag => `<span class="detail-tag">${tag.trim()}</span>`).join('') : '';
    
    const content = `
        <div class="file-details">
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-file"></i> File Name</div>
                <div class="detail-value">${file.original_filename}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-hdd"></i> File Size</div>
                <div class="detail-value">${formatFileSize(file.file_size)}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-file-code"></i> File Type</div>
                <div class="detail-value">${file.file_type || 'Unknown'}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-user"></i> Uploaded By</div>
                <div class="detail-value">${file.uploaded_by?.first_name || 'Unknown'} ${file.uploaded_by?.last_name || ''}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-calendar"></i> Upload Date</div>
                <div class="detail-value">${new Date(file.created_at).toLocaleString()}</div>
            </div>
            
            ${file.category ? `
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-tag"></i> Category</div>
                <div class="detail-value">${file.category.category_name}</div>
            </div>` : ''}
            
            ${file.driver ? `
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-user-tie"></i> Assigned Driver</div>
                <div class="detail-value">${file.driver.user.first_name} ${file.driver.user.last_name} (${file.driver.driver_id})</div>
            </div>` : ''}
            
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-download"></i> Downloads</div>
                <div class="detail-value">${file.download_count}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-globe"></i> Access</div>
                <div class="detail-value">${file.is_public ? 'Public' : 'Private'}</div>
            </div>
            
            ${file.last_accessed ? `
            <div class="detail-item">
                <div class="detail-label"><i class="fas fa-clock"></i> Last Accessed</div>
                <div class="detail-value">${new Date(file.last_accessed).toLocaleString()}</div>
            </div>` : ''}
            
            ${file.description ? `
            <div class="detail-item full">
                <div class="detail-label"><i class="fas fa-align-left"></i> Description</div>
                <div class="detail-value">${file.description}</div>
            </div>` : ''}
            
            ${tags ? `
            <div class="detail-item full">
                <div class="detail-label"><i class="fas fa-tags"></i> Tags</div>
                <div class="detail-tags">${tags}</div>
            </div>` : ''}
        </div>
        
        <div class="form-actions" style="margin-top: 30px;">
            <button class="btn btn-cancel" onclick="closeFileDetailsModal()">
                <i class="fas fa-times"></i> Close
            </button>
            <button class="btn btn-submit" onclick="downloadFile(${file.id})">
                <i class="fas fa-download"></i> Download File
            </button>
        </div>
    `;
    
    document.getElementById('fileDetailsContent').innerHTML = content;
    document.getElementById('fileDetailsModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeFileDetailsModal() {
    document.getElementById('fileDetailsModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Search and Filter Functionality
function searchFiles() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const fileCards = document.querySelectorAll('.file-card');
    
    fileCards.forEach(card => {
        const filename = card.dataset.filename || '';
        const category = card.dataset.category || '';
        
        if (filename.includes(searchTerm) || category.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Filter functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const fileCards = document.querySelectorAll('.file-card');
        
        fileCards.forEach(card => {
            if (filter === 'all' || card.dataset.fileType === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Search input event
document.getElementById('searchInput').addEventListener('input', searchFiles);

// Notification function
function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.notification').forEach(n => n.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#38a169' : type === 'error' ? '#e53e3e' : '#3182ce'};
        color: white;
        border-radius: 10px;
        box-shadow: var(--shadow);
        z-index: 1001;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        animation: slideIn 0.3s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
        closeFileDetailsModal();
    }
});

// Setup file drop on page load
document.addEventListener('DOMContentLoaded', function() {
    // Don't setup immediately, wait for modal to open
});
</script>
@endpush