@extends('layouts.admin')

@section('title', 'Driver Management')
@section('breadcrumb-section', 'Management')
@section('breadcrumb-current', 'Drivers')

@section('content')
<div class="drivers-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="page-title">
                    <i class="fas fa-users"></i>
                    Driver Management
                </h1>
                <p class="page-subtitle">Manage all drivers in your system</p>
            </div>
            <div class="header-right">
                <button type="button" class="btn btn-primary" onclick="openAddDriverModal()">
                    <i class="fas fa-plus"></i>
                    Add New Driver
                </button>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="filters-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search drivers...">
        </div>
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All Drivers</button>
            <button class="filter-btn" data-filter="active">Active</button>
            <button class="filter-btn" data-filter="inactive">Inactive</button>
            <button class="filter-btn" data-filter="paid">Paid</button>
            <button class="filter-btn" data-filter="unpaid">Unpaid</button>
        </div>
    </div>

    <!-- Drivers Grid -->
    <div class="drivers-grid" id="driversGrid">
        @forelse($drivers as $driver)
            <div class="driver-card" data-status="{{ $driver->driver->status ?? 'active' }}" data-payment="{{ $driver->driver->payment_status ?? 'unpaid' }}">
                <div class="driver-header">
                    <div class="driver-avatar">
                        {{ strtoupper(substr($driver->first_name, 0, 1) . substr($driver->last_name, 0, 1)) }}
                    </div>
                    <div class="driver-basic-info">
                        <h3>{{ $driver->first_name }} {{ $driver->last_name }}</h3>
                        <p class="driver-id">{{ $driver->driver->driver_id ?? 'N/A' }}</p>
                        <div class="status-badges">
                            <span class="status-badge {{ $driver->driver->status ?? 'active' }}">
                                {{ ucfirst($driver->driver->status ?? 'Active') }}
                            </span>
                            <span class="payment-badge {{ $driver->driver->payment_status ?? 'unpaid' }}">
                                {{ ucfirst($driver->driver->payment_status ?? 'Unpaid') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="expand-btn" onclick="toggleCard(this)">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>

                <div class="driver-details" style="display: none;">
                    <div class="details-grid">
                        <div class="detail-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <label>Email</label>
                                <span>{{ $driver->email }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <label>Phone</label>
                                <span>{{ $driver->phone ?: 'Not provided' }}</span>
                            </div>
                        </div>
                        @if($driver->driver)
                            <div class="detail-item">
                                <i class="fas fa-id-card"></i>
                                <div>
                                    <label>License Number</label>
                                    <span>{{ $driver->driver->license_number }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <label>License Expiry</label>
                                    <span class="{{ $driver->driver->license_expiry->isPast() ? 'expired' : '' }}">
                                        {{ $driver->driver->license_expiry->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-car"></i>
                                <div>
                                    <label>Vehicle</label>
                                    <span>{{ ucfirst($driver->driver->vehicle_type) }} - {{ $driver->driver->vehicle_plate }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar-plus"></i>
                                <div>
                                    <label>Hire Date</label>
                                    <span>{{ $driver->driver->hire_date->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-secondary">
                            <i class="fas fa-edit"></i>
                            Edit
                        </a>
                        <button class="btn btn-{{ ($driver->driver->payment_status ?? 'unpaid') === 'paid' ? 'warning' : 'success' }}" 
                                onclick="togglePaymentStatus({{ $driver->id }}, '{{ $driver->driver->payment_status ?? 'unpaid' }}')">
                            <i class="fas fa-dollar-sign"></i>
                            Mark as {{ ($driver->driver->payment_status ?? 'unpaid') === 'paid' ? 'Unpaid' : 'Paid' }}
                        </button>
                        <button class="btn btn-danger" onclick="deleteDriver({{ $driver->id }}, '{{ $driver->first_name }} {{ $driver->last_name }}')">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-drivers">
                <i class="fas fa-users-slash"></i>
                <h3>No Drivers Found</h3>
                <p>Get started by adding your first driver to the system.</p>
                <button type="button" class="btn btn-primary" onclick="openAddDriverModal()">
                    <i class="fas fa-plus"></i>
                    Add New Driver
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $drivers->links() }}
    </div>
</div>

<!-- Add Driver Modal -->
<div class="modal-overlay" id="addDriverModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>
                <i class="fas fa-user-plus"></i>
                Add New Driver
            </h2>
            <button type="button" class="modal-close" onclick="closeAddDriverModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addDriverForm" action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <div class="step-header">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                        <p>Enter the driver's basic details</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name">
                                <i class="fas fa-user"></i>
                                First Name *
                            </label>
                            <input type="text" id="first_name" name="first_name" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name">
                                <i class="fas fa-user"></i>
                                Last Name *
                            </label>
                            <input type="text" id="last_name" name="last_name" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email Address *
                            </label>
                            <input type="email" id="email" name="email" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i>
                                Phone Number *
                            </label>
                            <input type="tel" id="phone" name="phone" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group full-width">
                            <label for="address">
                                <i class="fas fa-map-marker-alt"></i>
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3" placeholder="Complete address"></textarea>
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Account Information -->
                <div class="form-step" data-step="2">
                    <div class="step-header">
                        <h3><i class="fas fa-key"></i> Account Information</h3>
                        <p>Set up login credentials for the driver</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="username">
                                <i class="fas fa-user-tag"></i>
                                Username *
                            </label>
                            <input type="text" id="username" name="username" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i>
                                Password *
                            </label>
                            <input type="password" id="password" name="password" required>
                            <div class="password-strength"></div>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i>
                                Confirm Password *
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Driver & Emergency Information -->
                <div class="form-step" data-step="3">
                    <div class="step-header">
                        <h3><i class="fas fa-id-card"></i> Driver & Emergency Information</h3>
                        <p>Enter license, vehicle, and emergency contact details</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="license_number">
                                <i class="fas fa-id-card"></i>
                                License Number *
                            </label>
                            <input type="text" id="license_number" name="license_number" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="license_expiry">
                                <i class="fas fa-calendar-alt"></i>
                                License Expiry *
                            </label>
                            <input type="date" id="license_expiry" name="license_expiry" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="vehicle_type">
                                <i class="fas fa-car"></i>
                                Vehicle Type *
                            </label>
                            <select id="vehicle_type" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="car">Car</option>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="truck">Truck</option>
                                <option value="van">Van</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="vehicle_plate">
                                <i class="fas fa-car"></i>
                                Vehicle Plate *
                            </label>
                            <input type="text" id="vehicle_plate" name="vehicle_plate" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact">
                                <i class="fas fa-user-friends"></i>
                                Emergency Contact Name
                            </label>
                            <input type="text" id="emergency_contact" name="emergency_contact" placeholder="Contact person name">
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="emergency_phone">
                                <i class="fas fa-phone-alt"></i>
                                Emergency Phone
                            </label>
                            <input type="tel" id="emergency_phone" name="emergency_phone" placeholder="Emergency contact phone">
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="hire_date">
                                <i class="fas fa-calendar-plus"></i>
                                Hire Date *
                            </label>
                            <input type="date" id="hire_date" name="hire_date" value="{{ date('Y-m-d') }}" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="monthly_salary">
                                <i class="fas fa-money-bill-wave"></i>
                                Monthly Salary
                            </label>
                            <input type="number" id="monthly_salary" name="monthly_salary" step="0.01" placeholder="0.00">
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="step-navigation">
                    <button type="button" class="btn btn-secondary" id="prevStep" onclick="previousStep()" style="display: none;">
                        <i class="fas fa-arrow-left"></i>
                        Previous
                    </button>
                    
                    <div class="step-indicators">
                        <span class="step-indicator active" data-step="1">1</span>
                        <span class="step-indicator" data-step="2">2</span>
                        <span class="step-indicator" data-step="3">3</span>
                    </div>
                    
                    <button type="button" class="btn btn-primary" id="nextStep" onclick="nextStep()">
                        Next
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                        <i class="fas fa-save"></i>
                        Create Driver
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Existing styles... */
.drivers-page {
    padding: 0;
}

.page-header {
    background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
    color: #1a202c;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
    min-height: 80px;
    transition: all 0.3s ease;
    background: white;
}

.form-group textarea:focus {
    outline: none;
    border-color: #FFD41D;
    box-shadow: 0 0 0 3px rgba(255, 212, 29, 0.1);
}

.form-group textarea.error {
    border-color: #D73535;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}

.page-title i {
    margin-right: 10px;
}

.page-subtitle {
    opacity: 0.8;
}

.btn {
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(66, 153, 225, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #a0aec0 0%, #718096 100%);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #D73535 0%, #c53030 100%);
    color: white;
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease-out;
}

.modal-overlay.show {
    display: flex;
}

.modal-container {
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
    animation: slideUp 0.3s ease-out;
}

.modal-header {
    background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 20px 20px 0 0;
    color: #1a202c;
}

.modal-header h2 {
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
    color: #1a202c;
}

.modal-close:hover {
    transform: rotate(90deg);
}

.modal-body {
    padding: 30px;
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: slideInRight 0.3s ease-out;
}

.step-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f5f9;
}

.step-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.step-header p {
    color: #718096;
    font-size: 14px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-group label i {
    color: #FFD41D;
    font-size: 14px;
}

.form-group input,
.form-group select {
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #FFD41D;
    box-shadow: 0 0 0 3px rgba(255, 212, 29, 0.1);
}

.form-group input.error,
.form-group select.error {
    border-color: #D73535;
}

.error-message {
    color: #D73535;
    font-size: 12px;
    margin-top: 5px;
    min-height: 18px;
}

.password-strength {
    margin-top: 5px;
    height: 4px;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.password-strength.weak {
    background: #D73535;
    width: 33%;
}

.password-strength.medium {
    background: #ed8936;
    width: 66%;
}

.password-strength.strong {
    background: #48bb78;
    width: 100%;
}

.modal-footer {
    background: #f8fafc;
    padding: 25px 30px;
    border-radius: 0 0 20px 20px;
}

.step-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.step-indicators {
    display: flex;
    gap: 15px;
}

.step-indicator {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #718096;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.step-indicator.active {
    background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
    color: #1a202c;
}

.step-indicator.completed {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
}

/* Filter and search styles */
.filters-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 300px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
}

.search-box input {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
}

.search-box input:focus {
    outline: none;
    border-color: #FFD41D;
}

.filter-buttons {
    display: flex;
    gap: 10px;
}

.filter-btn {
    padding: 8px 16px;
    border: 2px solid #e2e8f0;
    background: white;
    border-radius: 20px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn.active,
.filter-btn:hover {
    border-color: #FFD41D;
    background: #FFD41D;
    color: #1a202c;
}

/* Driver card styles */
.drivers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.driver-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.driver-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.driver-header {
    display: flex;
    align-items: center;
    padding: 20px;
    gap: 15px;
}

.driver-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #FFD41D 0%, #FFA240 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #1a202c;
    font-size: 18px;
}

.driver-basic-info {
    flex: 1;
}

.driver-basic-info h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2d3748;
}

.driver-id {
    color: #718096;
    font-size: 14px;
    margin-bottom: 10px;
}

.status-badges {
    display: flex;
    gap: 10px;
}

.status-badge,
.payment-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-badge.active {
    background: rgba(72, 187, 120, 0.1);
    color: #38a169;
}

.status-badge.inactive {
    background: rgba(160, 174, 192, 0.1);
    color: #718096;
}

.status-badge.suspended {
    background: rgba(215, 53, 53, 0.1);
    color: #D73535;
}

.payment-badge.paid {
    background: rgba(72, 187, 120, 0.1);
    color: #38a169;
}

.payment-badge.unpaid {
    background: rgba(237, 137, 54, 0.1);
    color: #dd6b20;
}

.expand-btn {
    background: none;
    border: none;
    color: #a0aec0;
    font-size: 16px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.expand-btn:hover {
    background: #f7fafc;
    color: #4a5568;
}

.expand-btn.expanded {
    transform: rotate(180deg);
}

.driver-details {
    border-top: 1px solid #e2e8f0;
    padding: 20px;
    background: #f8fafc;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.detail-item i {
    color: #FFD41D;
    margin-top: 2px;
}

.detail-item label {
    font-size: 12px;
    color: #718096;
    display: block;
    margin-bottom: 2px;
    text-transform: uppercase;
    font-weight: 600;
}

.detail-item span {
    color: #2d3748;
    font-weight: 500;
}

.detail-item span.expired {
    color: #D73535;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-buttons .btn {
    flex: 1;
    justify-content: center;
    min-width: 120px;
}

.no-drivers {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
}

.no-drivers i {
    font-size: 64px;
    color: #e2e8f0;
    margin-bottom: 20px;
}

.no-drivers h3 {
    font-size: 24px;
    color: #4a5568;
    margin-bottom: 10px;
}

.no-drivers p {
    color: #718096;
    margin-bottom: 30px;
}

.driver-card.hidden {
    display: none !important;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(50px) scale(0.9);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideInRight {
    from { 
        opacity: 0;
        transform: translateX(30px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        margin: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .step-navigation {
        flex-direction: column;
        gap: 15px;
    }
    
    .drivers-grid {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .filters-section {
        flex-direction: column;
        gap: 15px;
    }
    
    .search-box {
        max-width: none;
    }
    
    .action-buttons .btn {
        min-width: 100px;
    }
}
</style>
@endpush
@push('scripts')
<script>
// Modal functionality
let currentStep = 1;
const totalSteps = 3;

function openAddDriverModal() {
    document.getElementById('addDriverModal').classList.add('show');
    document.body.style.overflow = 'hidden';
    resetForm();
}

function closeAddDriverModal() {
    document.getElementById('addDriverModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    resetForm();
}

function resetForm() {
    currentStep = 1;
    const form = document.getElementById('addDriverForm');
    if (form) form.reset();
    
    // Reset steps
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    const firstStep = document.querySelector('.form-step[data-step="1"]');
    if (firstStep) firstStep.classList.add('active');
    
    // Reset indicators
    document.querySelectorAll('.step-indicator').forEach(indicator => {
        indicator.classList.remove('active', 'completed');
    });
    const firstIndicator = document.querySelector('.step-indicator[data-step="1"]');
    if (firstIndicator) firstIndicator.classList.add('active');
    
    // Reset buttons
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'inline-flex';
    if (submitBtn) submitBtn.style.display = 'none';
    
    // Clear errors
    document.querySelectorAll('.error-message').forEach(error => error.textContent = '');
    document.querySelectorAll('.form-group input, .form-group select').forEach(input => {
        input.classList.remove('error');
    });
}

function nextStep() {
    if (true) { 
        if (currentStep < totalSteps) {
            
            const currentIndicator = document.querySelector(`.step-indicator[data-step="${currentStep}"]`);
            if (currentIndicator) {
                currentIndicator.classList.add('completed');
                currentIndicator.classList.remove('active');
            }
            
            const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
            if (currentStepElement) {
                currentStepElement.classList.remove('active');
            }
            
            currentStep++;
            console.log('Now moving to step:', currentStep);
            
            // Show next step
            const nextStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
            const nextIndicator = document.querySelector(`.step-indicator[data-step="${currentStep}"]`);
            
            if (nextStepElement) {
                nextStepElement.classList.add('active');
                console.log('Added active class to step', currentStep);
            } else {
                console.log('Could not find step element for step', currentStep);
            }
            
            if (nextIndicator) {
                nextIndicator.classList.add('active');
                console.log('Added active class to indicator', currentStep);
            } else {
                console.log('Could not find indicator for step', currentStep);
            }
            
            // Update buttons
            const prevBtn = document.getElementById('prevStep');
            const nextBtn = document.getElementById('nextStep');
            const submitBtn = document.getElementById('submitBtn');
            
            if (prevBtn) prevBtn.style.display = 'inline-flex';
            
            if (currentStep === totalSteps) {
                if (nextBtn) nextBtn.style.display = 'none';
                if (submitBtn) submitBtn.style.display = 'inline-flex';
            }
            
            console.log('Step navigation completed');
        }
    } else {
        console.log('Validation failed for step:', currentStep);
    }
}

function previousStep() {
    if (currentStep > 1) {
        console.log('Going back from step:', currentStep);
        
        // Remove active from current step
        const currentIndicator = document.querySelector(`.step-indicator[data-step="${currentStep}"]`);
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        
        if (currentIndicator) currentIndicator.classList.remove('active');
        if (currentStepElement) currentStepElement.classList.remove('active');
        
        // Move to previous step
        currentStep--;
        console.log('Now at step:', currentStep);
        
        // Show previous step
        const prevStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const prevIndicator = document.querySelector(`.step-indicator[data-step="${currentStep}"]`);
        
        if (prevStepElement) prevStepElement.classList.add('active');
        if (prevIndicator) {
            prevIndicator.classList.add('active');
            prevIndicator.classList.remove('completed');
        }
        
        // Update buttons
        const prevBtn = document.getElementById('prevStep');
        const nextBtn = document.getElementById('nextStep');
        const submitBtn = document.getElementById('submitBtn');
        
        if (currentStep === 1 && prevBtn) {
            prevBtn.style.display = 'none';
        }
        
        if (nextBtn) nextBtn.style.display = 'inline-flex';
        if (submitBtn) submitBtn.style.display = 'none';
    }
}

function validateCurrentStep() {
    console.log('Validating step:', currentStep);
    
    const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    if (!currentStepElement) {
        console.log('No step element found for step:', currentStep);
        return false;
    }
    
    const requiredInputs = currentStepElement.querySelectorAll('input[required], select[required]');
    console.log('Found required inputs:', requiredInputs.length);
    
    let isValid = true;
    
    requiredInputs.forEach((input, index) => {
        console.log(`Checking input ${index}:`, input.name, 'Value:', input.value);
        
        const errorElement = input.parentElement.querySelector('.error-message');
        
        // Clear previous errors
        input.classList.remove('error');
        if (errorElement) errorElement.textContent = '';
        
        // Check if field is empty
        if (!input.value || input.value.trim() === '') {
            console.log('Field is empty:', input.name);
            input.classList.add('error');
            if (errorElement) {
                errorElement.textContent = 'This field is required';
            }
            isValid = false;
        }
        
        // Email validation
        if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
            console.log('Invalid email format:', input.value);
            input.classList.add('error');
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid email address';
            }
            isValid = false;
        }
        
        // Password confirmation
        if (input.name === 'password_confirmation') {
            const passwordField = document.getElementById('password');
            if (passwordField && input.value !== passwordField.value) {
                console.log('Passwords do not match');
                input.classList.add('error');
                if (errorElement) {
                    errorElement.textContent = 'Passwords do not match';
                }
                isValid = false;
            }
        }
    });
    
    console.log('Validation result:', isValid);
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Test function - remove validation temporarily
function testNextStep() {
    console.log('Test next step - bypassing validation');
    currentStep = Math.min(currentStep + 1, totalSteps);
    
    // Hide all steps
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    
    // Show current step
    const targetStep = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    if (targetStep) {
        targetStep.classList.add('active');
        console.log('Showing step:', currentStep);
    }
    
    // Update indicators
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNum = index + 1;
        indicator.classList.remove('active', 'completed');
        
        if (stepNum < currentStep) {
            indicator.classList.add('completed');
        } else if (stepNum === currentStep) {
            indicator.classList.add('active');
        }
    });
    
    // Update buttons
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-flex' : 'none';
    if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'inline-flex' : 'none';
    if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing modal...');
    
    // Test if elements exist
    const modal = document.getElementById('addDriverModal');
    const form = document.getElementById('addDriverForm');
    const nextBtn = document.getElementById('nextStep');
    
    console.log('Modal exists:', !!modal);
    console.log('Form exists:', !!form);
    console.log('Next button exists:', !!nextBtn);
    
    if (nextBtn) {
        // Replace the onclick with a direct event listener for testing
        nextBtn.onclick = null;
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Next button clicked via event listener');
            nextStep();
        });
    }
});

// Temporary: Replace nextStep with testNextStep for debugging
window.testNext = testNextStep;

// Auto-generate username
function generateUsername() {
    const firstName = document.getElementById('first_name')?.value?.trim();
    const lastName = document.getElementById('last_name')?.value?.trim();
    const usernameField = document.getElementById('username');
    
    if (firstName && lastName && usernameField && !usernameField.value) {
        const username = (firstName + '.' + lastName).toLowerCase().replace(/\s+/g, '');
        usernameField.value = username;
    }
}

// Password strength
function updatePasswordStrength() {
    const passwordField = document.getElementById('password');
    if (passwordField) {
        const password = passwordField.value;
        const strengthElement = passwordField.parentElement.querySelector('.password-strength');
        if (strengthElement) {
            let strength = '';
            if (password.length > 0) {
                let score = 0;
                if (password.length >= 8) score++;
                if (/[A-Z]/.test(password)) score++;
                if (/[a-z]/.test(password)) score++;
                if (/[0-9]/.test(password)) score++;
                if (/[^A-Za-z0-9]/.test(password)) score++;
                
                if (score <= 2) strength = 'weak';
                else if (score <= 4) strength = 'medium';
                else strength = 'strong';
            }
            strengthElement.className = `password-strength ${strength}`;
        }
    }
}

// Close modal handlers
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddDriverModal();
    }
});

// Close when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('addDriverModal');
    if (e.target === modal) {
        closeAddDriverModal();
    }
});

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addDriverForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('=== FORM SUBMISSION STARTED ===');
            
            // Check if we're on the final step
            if (currentStep !== totalSteps) {
                console.log('Not on final step, preventing submission');
                return;
            }
            
            // Final validation
            if (!validateCurrentStep()) {
                console.log('Final validation failed');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
                submitBtn.disabled = true;
            }
            
            try {
                const formData = new FormData(this);
                
                // Log all form data
                console.log('=== FORM DATA ===');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
                // Check CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('CSRF token element exists:', !!csrfToken);
                if (csrfToken) {
                    console.log('CSRF token value:', csrfToken.getAttribute('content'));
                }
                
                console.log('Form action URL:', this.action);
                console.log('Making fetch request...');
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                console.log('Response received:');
                console.log('Status:', response.status);
                console.log('Status Text:', response.statusText);
                console.log('OK:', response.ok);
                
                const responseText = await response.text();
                console.log('Raw response:', responseText);
                
                let responseData;
                try {
                    responseData = JSON.parse(responseText);
                    console.log('Parsed response:', responseData);
                } catch (parseError) {
                    console.error('Failed to parse JSON:', parseError);
                    responseData = { success: false, message: 'Invalid JSON response' };
                }
                
                if (response.ok && responseData.success) {
                    console.log('SUCCESS! Driver created successfully');
                    alert('Driver created successfully!');
                    closeAddDriverModal();
                    window.location.reload();
                } else {
                    console.error('Server responded with error:', responseData);
                    
                    if (responseData.errors) {
                        console.log('Validation errors:', responseData.errors);
                        handleFormErrors(responseData.errors);
                    } else if (responseData.message) {
                        alert('Error: ' + responseData.message);
                    } else {
                        alert('An unknown error occurred. Check the console for details.');
                    }
                }
                
            } catch (error) {
                console.error('Network/JavaScript error:', error);
                alert('A network error occurred: ' + error.message);
            } finally {
                // Reset button
                if (submitBtn) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
                console.log('=== FORM SUBMISSION ENDED ===');
            }
        });
    } else {
        console.error('Form element not found!');
    }
});

// Existing search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const driverCards = document.querySelectorAll('.driver-card');
    
    function performSearch() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        driverCards.forEach(card => {
            const driverNameElement = card.querySelector('.driver-basic-info h3');
            const driverIdElement = card.querySelector('.driver-id');
            
            const driverName = driverNameElement ? driverNameElement.textContent.toLowerCase() : '';
            const driverId = driverIdElement ? driverIdElement.textContent.toLowerCase() : '';
            
            const matchesSearch = driverName.includes(searchTerm) || 
                                driverId.includes(searchTerm) ||
                                searchTerm === '';
            
            if (matchesSearch) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
        
        const activeFilter = document.querySelector('.filter-btn.active');
        if (activeFilter && activeFilter.dataset.filter !== 'all') {
            performFilter(activeFilter.dataset.filter);
        }
    }
    
    function performFilter(filterType) {
        driverCards.forEach(card => {
            if (card.classList.contains('hidden')) {
                return;
            }
            
            let shouldShow = true;
            
            switch(filterType) {
                case 'all':
                    shouldShow = true;
                    break;
                case 'active':
                case 'inactive':
                case 'suspended':
                    shouldShow = card.dataset.status === filterType;
                    break;
                case 'paid':
                case 'unpaid':
                    shouldShow = card.dataset.payment === filterType;
                    break;
                default:
                    shouldShow = true;
            }
            
            if (shouldShow) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
    }
    
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filterType = this.dataset.filter;
            
            driverCards.forEach(card => {
                card.style.display = 'block';
                card.classList.remove('hidden');
            });
            
            if (searchInput && searchInput.value.trim() !== '') {
                performSearch();
            }
            
            performFilter(filterType);
        });
    });
});

// Existing card functionality
function toggleCard(button) {
    const card = button.closest('.driver-card');
    const details = card.querySelector('.driver-details');
    const isExpanded = details.style.display !== 'none';
    
    if (isExpanded) {
        details.style.display = 'none';
        button.classList.remove('expanded');
    } else {
        details.style.display = 'block';
        button.classList.add('expanded');
    }
}

async function togglePaymentStatus(driverId, currentStatus) {
    try {
        const response = await fetch(`/drivers/${driverId}/toggle-payment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload(); // Simple reload for now
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to update payment status. Please try again.');
    }
}

function deleteDriver(driverId, driverName) {
    if (confirm(`Are you sure you want to delete ${driverName}? This action cannot be undone.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/drivers/${driverId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush