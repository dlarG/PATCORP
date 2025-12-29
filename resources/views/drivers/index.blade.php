@extends('layouts.admin')

@section('title', 'Driver Management')
@section('breadcrumb-section', 'Management')
@section('breadcrumb-current', 'Drivers')

@section('content')
<div class="drivers-page">
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
                        <button class="btn btn-secondary" onclick="openEditDriverModal({{ $driver->id }})">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
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
    <div class="pagination-wrapper">
        {{ $drivers->links() }}
    </div>
</div>
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

<div class="modal-overlay" id="editDriverModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>
                <i class="fas fa-user-edit"></i>
                Edit Driver
            </h2>
            <button type="button" class="modal-close" onclick="closeEditDriverModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editDriverForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-step active" data-step="1">
                    <div class="step-header">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                        <p>Update the driver's basic details</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_first_name">
                                <i class="fas fa-user"></i>
                                First Name *
                            </label>
                            <input type="text" id="edit_first_name" name="first_name" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_last_name">
                                <i class="fas fa-user"></i>
                                Last Name *
                            </label>
                            <input type="text" id="edit_last_name" name="last_name" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_email">
                                <i class="fas fa-envelope"></i>
                                Email Address *
                            </label>
                            <input type="email" id="edit_email" name="email" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_phone">
                                <i class="fas fa-phone"></i>
                                Phone Number
                            </label>
                            <input type="tel" id="edit_phone" name="phone">
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group full-width">
                            <label for="edit_address">
                                <i class="fas fa-map-marker-alt"></i>
                                Address
                            </label>
                            <textarea id="edit_address" name="address" rows="3" placeholder="Complete address"></textarea>
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>
                <div class="form-step" data-step="2">
                    <div class="step-header">
                        <h3><i class="fas fa-key"></i> Account Information</h3>
                        <p>Update login credentials</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="edit_username">
                                <i class="fas fa-user-tag"></i>
                                Username *
                            </label>
                            <input type="text" id="edit_username" name="username" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_password">
                                <i class="fas fa-lock"></i>
                                New Password
                            </label>
                            <input type="password" id="edit_password" name="password">
                            <small class="form-text">Leave blank to keep current password</small>
                            <div class="password-strength"></div>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_password_confirmation">
                                <i class="fas fa-lock"></i>
                                Confirm New Password
                            </label>
                            <input type="password" id="edit_password_confirmation" name="password_confirmation">
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>
                <div class="form-step" data-step="3">
                    <div class="step-header">
                        <h3><i class="fas fa-id-card"></i> Driver & Emergency Information</h3>
                        <p>Update license, vehicle, and emergency contact details</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_license_number">
                                <i class="fas fa-id-card"></i>
                                License Number *
                            </label>
                            <input type="text" id="edit_license_number" name="license_number" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_license_expiry">
                                <i class="fas fa-calendar-alt"></i>
                                License Expiry *
                            </label>
                            <input type="date" id="edit_license_expiry" name="license_expiry" required>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_vehicle_type">
                                <i class="fas fa-car"></i>
                                Vehicle Type *
                            </label>
                            <select id="edit_vehicle_type" name="vehicle_type" required>
                                <option value="">Select Vehicle Type</option>
                                <option value="car">Car</option>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="truck">Truck</option>
                                <option value="van">Van</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_vehicle_plate">
                                <i class="fas fa-car"></i>
                                Vehicle Plate *
                            </label>
                            <input type="text" id="edit_vehicle_plate" name="vehicle_plate" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="edit_emergency_contact">
                                <i class="fas fa-user-friends"></i>
                                Emergency Contact Name
                            </label>
                            <input type="text" id="edit_emergency_contact" name="emergency_contact" placeholder="Contact person name">
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="edit_emergency_phone">
                                <i class="fas fa-phone-alt"></i>
                                Emergency Phone
                            </label>
                            <input type="tel" id="edit_emergency_phone" name="emergency_phone" placeholder="Emergency contact phone">
                            <div class="error-message"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_hire_date">
                                <i class="fas fa-calendar-plus"></i>
                                Hire Date *
                            </label>
                            <input type="date" id="edit_hire_date" name="hire_date" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="form-group">
                            <label for="edit_status">
                                <i class="fas fa-toggle-on"></i>
                                Status *
                            </label>
                            <select id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                            <div class="error-message"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="step-navigation">
                    <button type="button" class="btn btn-secondary" id="editPrevStep" style="display: none;">
                        <i class="fas fa-arrow-left"></i>
                        Previous
                    </button>
                    
                    <div class="step-indicators">
                        <span class="step-indicator active" data-step="1">1</span>
                        <span class="step-indicator" data-step="2">2</span>
                        <span class="step-indicator" data-step="3">3</span>
                    </div>
                    
                    <button type="button" class="btn btn-primary" id="editNextStep">
                        Next
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <button type="submit" class="btn btn-success" id="editSubmitBtn" style="display: none;">
                        <i class="fas fa-save"></i>
                        Update Driver
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
.form-text {
    font-size: 12px;
    color: #718096;
    margin-top: 4px;
    font-style: italic;
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
    
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    const firstStep = document.querySelector('.form-step[data-step="1"]');
    if (firstStep) firstStep.classList.add('active');
    
    document.querySelectorAll('.step-indicator').forEach(indicator => {
        indicator.classList.remove('active', 'completed');
    });
    const firstIndicator = document.querySelector('.step-indicator[data-step="1"]');
    if (firstIndicator) firstIndicator.classList.add('active');
    
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'inline-flex';
    if (submitBtn) submitBtn.style.display = 'none';
    
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
        
        const currentIndicator = document.querySelector(`.step-indicator[data-step="${currentStep}"]`);
        const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        
        if (currentIndicator) currentIndicator.classList.remove('active');
        if (currentStepElement) currentStepElement.classList.remove('active');
        
        currentStep--;
        
        const prevStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
        const prevIndicator = document.querySelector(`.step-indicator[data-step="${currentStep}"]`);
        
        if (prevStepElement) prevStepElement.classList.add('active');
        if (prevIndicator) {
            prevIndicator.classList.add('active');
            prevIndicator.classList.remove('completed');
        }
        
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
        
        input.classList.remove('error');
        if (errorElement) errorElement.textContent = '';
        
        if (!input.value || input.value.trim() === '') {
            console.log('Field is empty:', input.name);
            input.classList.add('error');
            if (errorElement) {
                errorElement.textContent = 'This field is required';
            }
            isValid = false;
        }

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

function testNextStep() {
    console.log('Test next step - bypassing validation');
    currentStep = Math.min(currentStep + 1, totalSteps);
    
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    
    const targetStep = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    if (targetStep) {
        targetStep.classList.add('active');
        console.log('Showing step:', currentStep);
    }
    
    document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
        const stepNum = index + 1;
        indicator.classList.remove('active', 'completed');
        
        if (stepNum < currentStep) {
            indicator.classList.add('completed');
        } else if (stepNum === currentStep) {
            indicator.classList.add('active');
        }
    });
    
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'inline-flex' : 'none';
    if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'inline-flex' : 'none';
    if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
}

function handleFormErrors(errors) {
    console.log('Handling form errors:', errors);
    for (const [field, messages] of Object.entries(errors)) {
        const input = document.querySelector(`[name="${field}"]`);
        const errorElement = input ? input.parentElement.querySelector('.error-message') : null;
        
        if (input) {
            input.classList.add('error');
        }
        
        if (errorElement) {
            errorElement.textContent = messages.join(' ');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing modal...');
    
    const modal = document.getElementById('addDriverModal');
    const form = document.getElementById('addDriverForm');
    const nextBtn = document.getElementById('nextStep');
    
    const editModal = document.getElementById('editDriverModal');
    const editForm = document.getElementById('editDriverForm');
    const editNextBtn = document.getElementById('editNextStep');
    const editPrevBtn = document.getElementById('editPrevStep');
    
    console.log('Modal exists:', !!modal);
    console.log('Form exists:', !!form);
    console.log('Next button exists:', !!nextBtn);
    console.log('Edit Modal exists:', !!editModal);
    console.log('Edit Form exists:', !!editForm);
    console.log('Edit Next button exists:', !!editNextBtn);
    console.log('Edit Prev button exists:', !!editPrevBtn);
    
    if (nextBtn) {
        nextBtn.onclick = null; 
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Next button clicked via event listener');
            nextStep();
        });
    }
    
    if (editNextBtn) {
        editNextBtn.onclick = null; 
        editNextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Edit next button clicked via event listener');
            editNextStep();
        });
    }
    
    if (editPrevBtn) {
        editPrevBtn.onclick = null; 
        editPrevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Edit previous button clicked via event listener');
            editPreviousStep();
        });
    }
});

window.testNext = testNextStep;

function generateUsername() {
    const firstName = document.getElementById('first_name')?.value?.trim();
    const lastName = document.getElementById('last_name')?.value?.trim();
    const usernameField = document.getElementById('username');
    
    if (firstName && lastName && usernameField && !usernameField.value) {
        const username = (firstName + '.' + lastName).toLowerCase().replace(/\s+/g, '');
        usernameField.value = username;
    }
}

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

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddDriverModal();
    }
});

window.addEventListener('click', function(e) {
    const modal = document.getElementById('addDriverModal');
    if (e.target === modal) {
        closeAddDriverModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addDriverForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('=== FORM SUBMISSION STARTED ===');
            
            if (currentStep !== totalSteps) {
                console.log('Not on final step, preventing submission');
                return;
            }
            
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
                
                console.log('=== FORM DATA ===');
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
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

let editCurrentStep = 1;
const editTotalSteps = 3;
let currentDriverId = null;

function openEditDriverModal(driverId) {
    currentDriverId = driverId;
    
    // Show loading state
    const modal = document.getElementById('editDriverModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Reset form first
    resetEditForm();
    
    // Load driver data
    loadDriverData(driverId);
}

function closeEditDriverModal() {
    document.getElementById('editDriverModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    resetEditForm();
    currentDriverId = null;
}

function resetEditForm() {
    editCurrentStep = 1;
    const form = document.getElementById('editDriverForm');
    if (form) form.reset();
    
    // Reset steps
    document.querySelectorAll('#editDriverModal .form-step').forEach(step => {
        step.classList.remove('active');
    });
    const firstStep = document.querySelector('#editDriverModal .form-step[data-step="1"]');
    if (firstStep) firstStep.classList.add('active');
    
    // Reset indicators
    document.querySelectorAll('#editDriverModal .step-indicator').forEach(indicator => {
        indicator.classList.remove('active', 'completed');
    });
    const firstIndicator = document.querySelector('#editDriverModal .step-indicator[data-step="1"]');
    if (firstIndicator) firstIndicator.classList.add('active');
    
    // Reset buttons
    const prevBtn = document.getElementById('editPrevStep');
    const nextBtn = document.getElementById('editNextStep');
    const submitBtn = document.getElementById('editSubmitBtn');
    
    if (prevBtn) prevBtn.style.display = 'none';
    if (nextBtn) nextBtn.style.display = 'inline-flex';
    if (submitBtn) submitBtn.style.display = 'none';
    
    // Clear errors
    document.querySelectorAll('#editDriverModal .error-message').forEach(error => error.textContent = '');
    document.querySelectorAll('#editDriverModal .form-group input, #editDriverModal .form-group select, #editDriverModal .form-group textarea').forEach(input => {
        input.classList.remove('error');
    });
}

async function loadDriverData(driverId) {
    try {
        console.log('Loading driver data for ID:', driverId);
        
        const response = await fetch(`/drivers/${driverId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load driver data');
        }

        const data = await response.json();
        console.log('Driver data loaded:', data);
        
        populateEditForm(data.driver);
        
    } catch (error) {
        console.error('Error loading driver data:', error);
        alert('Failed to load driver data. Please try again.');
        closeEditDriverModal();
    }
}

function populateEditForm(driver) {
    console.log('Populating edit form with driver data:', driver);
    
    // Set form action
    const form = document.getElementById('editDriverForm');
    form.action = `/drivers/${driver.id}`;
    
    // Populate basic info
    document.getElementById('edit_first_name').value = driver.first_name || '';
    document.getElementById('edit_last_name').value = driver.last_name || '';
    document.getElementById('edit_email').value = driver.email || '';
    document.getElementById('edit_phone').value = driver.phone || '';
    document.getElementById('edit_username').value = driver.username || '';
    
    // Populate driver info if driver record exists
    if (driver.driver) {
        console.log('Driver record found:', driver.driver);
        
        // Address field
        const addressField = document.getElementById('edit_address');
        if (addressField) {
            addressField.value = driver.driver.address || '';
        }
        
        // License fields
        const licenseNumberField = document.getElementById('edit_license_number');
        if (licenseNumberField) {
            licenseNumberField.value = driver.driver.license_number || '';
        }
        
        const licenseExpiryField = document.getElementById('edit_license_expiry');
        if (licenseExpiryField) {
            // Format date for input[type="date"] (YYYY-MM-DD)
            let licenseExpiry = driver.driver.license_expiry;
            if (licenseExpiry) {
                // Convert to YYYY-MM-DD format if it's not already
                if (licenseExpiry.includes('/')) {
                    const dateParts = licenseExpiry.split('/');
                    licenseExpiry = `${dateParts[2]}-${dateParts[0].padStart(2, '0')}-${dateParts[1].padStart(2, '0')}`;
                } else if (licenseExpiry.includes('-') && licenseExpiry.length === 10) {
                    licenseExpiry = licenseExpiry; // Already in correct format
                } else {
                    // Try to parse and format
                    const date = new Date(licenseExpiry);
                    if (!isNaN(date.getTime())) {
                        licenseExpiry = date.toISOString().split('T')[0];
                    }
                }
            }
            licenseExpiryField.value = licenseExpiry || '';
        }
        
        // Vehicle fields
        const vehicleTypeField = document.getElementById('edit_vehicle_type');
        if (vehicleTypeField) {
            vehicleTypeField.value = driver.driver.vehicle_type || '';
        }
        
        const vehiclePlateField = document.getElementById('edit_vehicle_plate');
        if (vehiclePlateField) {
            vehiclePlateField.value = driver.driver.vehicle_plate || '';
        }
        
        // Emergency contact fields
        const emergencyContactField = document.getElementById('edit_emergency_contact');
        if (emergencyContactField) {
            emergencyContactField.value = driver.driver.emergency_contact || '';
        }
        
        const emergencyPhoneField = document.getElementById('edit_emergency_phone');
        if (emergencyPhoneField) {
            emergencyPhoneField.value = driver.driver.emergency_phone || '';
        }
        
        // Hire date field
        const hireDateField = document.getElementById('edit_hire_date');
        if (hireDateField) {
            let hireDate = driver.driver.hire_date;
            if (hireDate) {
                // Format date for input[type="date"] (YYYY-MM-DD)
                if (hireDate.includes('/')) {
                    const dateParts = hireDate.split('/');
                    hireDate = `${dateParts[2]}-${dateParts[0].padStart(2, '0')}-${dateParts[1].padStart(2, '0')}`;
                } else if (hireDate.includes('-') && hireDate.length === 10) {
                    hireDate = hireDate; // Already in correct format
                } else {
                    // Try to parse and format
                    const date = new Date(hireDate);
                    if (!isNaN(date.getTime())) {
                        hireDate = date.toISOString().split('T')[0];
                    }
                }
            }
            hireDateField.value = hireDate || '';
        }
        
        // Status field
        const statusField = document.getElementById('edit_status');
        if (statusField) {
            statusField.value = driver.driver.status || 'active';
        }
        
        console.log('All fields populated successfully');
    } else {
        console.log('No driver record found for this user');
        const fieldsTosClear = [
            'edit_address', 'edit_license_number', 'edit_license_expiry',
            'edit_vehicle_type', 'edit_vehicle_plate', 'edit_emergency_contact',
            'edit_emergency_phone', 'edit_hire_date'
        ];
        
        fieldsTosClear.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = '';
            }
        });
    }
}

function editNextStep() {
    console.log('Edit next step clicked, current step:', editCurrentStep);
    
    if (validateEditCurrentStep()) {
        if (editCurrentStep < editTotalSteps) {
            // Mark current step as completed
            const currentIndicator = document.querySelector(`#editDriverModal .step-indicator[data-step="${editCurrentStep}"]`);
            if (currentIndicator) {
                currentIndicator.classList.add('completed');
                currentIndicator.classList.remove('active');
            }
            
            // Hide current step
            const currentStepElement = document.querySelector(`#editDriverModal .form-step[data-step="${editCurrentStep}"]`);
            if (currentStepElement) {
                currentStepElement.classList.remove('active');
            }
            
            // Move to next step
            editCurrentStep++;
            console.log('Edit: Now moving to step:', editCurrentStep);
            
            // Show next step
            const nextStepElement = document.querySelector(`#editDriverModal .form-step[data-step="${editCurrentStep}"]`);
            const nextIndicator = document.querySelector(`#editDriverModal .step-indicator[data-step="${editCurrentStep}"]`);
            
            if (nextStepElement) {
                nextStepElement.classList.add('active');
                console.log('Edit: Added active class to step', editCurrentStep);
            } else {
                console.log('Edit: Could not find step element for step', editCurrentStep);
            }
            
            if (nextIndicator) {
                nextIndicator.classList.add('active');
                console.log('Edit: Added active class to indicator', editCurrentStep);
            } else {
                console.log('Edit: Could not find indicator for step', editCurrentStep);
            }
            
            // Update buttons
            const prevBtn = document.getElementById('editPrevStep');
            const nextBtn = document.getElementById('editNextStep');
            const submitBtn = document.getElementById('editSubmitBtn');
            
            if (prevBtn) prevBtn.style.display = 'inline-flex';
            
            if (editCurrentStep === editTotalSteps) {
                if (nextBtn) nextBtn.style.display = 'none';
                if (submitBtn) submitBtn.style.display = 'inline-flex';
            }
            
            console.log('Edit: Step navigation completed');
        }
    } else {
        console.log('Edit: Validation failed for step:', editCurrentStep);
    }
}

function editPreviousStep() {
    if (editCurrentStep > 1) {
        // Remove active from current step
        const currentIndicator = document.querySelector(`#editDriverModal .step-indicator[data-step="${editCurrentStep}"]`);
        const currentStepElement = document.querySelector(`#editDriverModal .form-step[data-step="${editCurrentStep}"]`);
        
        if (currentIndicator) currentIndicator.classList.remove('active');
        if (currentStepElement) currentStepElement.classList.remove('active');
        
        // Move to previous step
        editCurrentStep--;
        
        // Show previous step
        const prevStepElement = document.querySelector(`#editDriverModal .form-step[data-step="${editCurrentStep}"]`);
        const prevIndicator = document.querySelector(`#editDriverModal .step-indicator[data-step="${editCurrentStep}"]`);
        
        if (prevStepElement) prevStepElement.classList.add('active');
        if (prevIndicator) {
            prevIndicator.classList.add('active');
            prevIndicator.classList.remove('completed');
        }
        
        // Update buttons
        const prevBtn = document.getElementById('editPrevStep');
        const nextBtn = document.getElementById('editNextStep');
        const submitBtn = document.getElementById('editSubmitBtn');
        
        if (editCurrentStep === 1 && prevBtn) {
            prevBtn.style.display = 'none';
        }
        
        if (nextBtn) nextBtn.style.display = 'inline-flex';
        if (submitBtn) submitBtn.style.display = 'none';
    }
}

function validateEditCurrentStep() {
    console.log('Validating edit step:', editCurrentStep);
    
    const currentStepElement = document.querySelector(`#editDriverModal .form-step[data-step="${editCurrentStep}"]`);
    if (!currentStepElement) {
        console.log('No step element found for edit step:', editCurrentStep);
        return false;
    }
    
    const requiredInputs = currentStepElement.querySelectorAll('input[required], select[required]');
    console.log('Found required inputs in edit form:', requiredInputs.length);
    
    let isValid = true;
    
    requiredInputs.forEach((input, index) => {
        console.log(`Checking edit input ${index}:`, input.name, 'Value:', input.value);
        
        const errorElement = input.parentElement.querySelector('.error-message');
        
        // Clear previous errors
        input.classList.remove('error');
        if (errorElement) errorElement.textContent = '';
        
        // Check if field is empty
        if (!input.value || input.value.trim() === '') {
            console.log('Edit field is empty:', input.name);
            input.classList.add('error');
            if (errorElement) {
                errorElement.textContent = 'This field is required';
            }
            isValid = false;
            return;
        }
        
        // Email validation
        if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
            console.log('Invalid email format in edit:', input.value);
            input.classList.add('error');
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid email address';
            }
            isValid = false;
        }
        
        // Password confirmation (only if password is being changed)
        if (input.name === 'password_confirmation') {
            const passwordField = document.getElementById('edit_password');
            if (passwordField && passwordField.value && input.value !== passwordField.value) {
                console.log('Passwords do not match in edit');
                input.classList.add('error');
                if (errorElement) {
                    errorElement.textContent = 'Passwords do not match';
                }
                isValid = false;
            }
        }
        
        // License expiry validation
        if (input.name === 'license_expiry' && input.value) {
            const today = new Date();
            const inputDate = new Date(input.value);
            if (inputDate <= today) {
                console.log('License expiry date must be in the future in edit');
                input.classList.add('error');
                if (errorElement) {
                    errorElement.textContent = 'License expiry date must be in the future';
                }
                isValid = false;
            }
        }
    });
    
    console.log('Edit validation result:', isValid);
    return isValid;
}

// Edit form submission
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editDriverForm');
    if (editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('=== EDIT FORM SUBMISSION STARTED ===');
            
            // Check if we're on the final step
            if (editCurrentStep !== editTotalSteps) {
                console.log('Not on final edit step, preventing submission');
                return;
            }
            
            // Final validation
            if (!validateEditCurrentStep()) {
                console.log('Final edit validation failed');
                return;
            }
            
            const submitBtn = document.getElementById('editSubmitBtn');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                submitBtn.disabled = true;
            }
            
            try {
                const formData = new FormData(this);
                
                // Log form data for debugging
                console.log('Edit form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('CSRF token exists:', !!csrfToken);
                console.log('Edit form action:', this.action);
                
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                console.log('Edit response status:', response.status);
                console.log('Edit response ok:', response.ok);
                
                const responseText = await response.text();
                console.log('Edit raw response:', responseText);
                
                let responseData;
                try {
                    responseData = JSON.parse(responseText);
                    console.log('Edit parsed response:', responseData);
                } catch (parseError) {
                    console.error('Failed to parse edit JSON:', parseError);
                    responseData = { success: false, message: 'Invalid JSON response' };
                }
                
                if (response.ok && (responseData.success !== false)) {
                    console.log('SUCCESS! Driver updated successfully');
                    alert('Driver updated successfully!');
                    closeEditDriverModal();
                    window.location.reload();
                } else {
                    console.error('Server responded with edit error:', responseData);
                    
                    if (responseData.errors) {
                        console.log('Edit validation errors:', responseData.errors);
                        handleEditFormErrors(responseData.errors);
                    } else if (responseData.message) {
                        alert('Error: ' + responseData.message);
                    } else {
                        alert('An unknown error occurred. Check the console for details.');
                    }
                }
                
            } catch (error) {
                console.error('Edit network/JavaScript error:', error);
                alert('A network error occurred: ' + error.message);
            } finally {
                // Reset button
                if (submitBtn) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
                console.log('=== EDIT FORM SUBMISSION ENDED ===');
            }
        });
    }
});

function handleEditFormErrors(errors) {
    console.log('Handling edit form errors:', errors);
    
    // Clear all previous errors
    document.querySelectorAll('#editDriverModal .error-message').forEach(error => error.textContent = '');
    document.querySelectorAll('#editDriverModal .form-group input, #editDriverModal .form-group select, #editDriverModal .form-group textarea').forEach(input => {
        input.classList.remove('error');
    });
    
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`#editDriverModal [name="${fieldName}"]`);
        if (field) {
            const errorElement = field.parentElement.querySelector('.error-message');
            field.classList.add('error');
            if (errorElement) {
                errorElement.textContent = errors[fieldName][0];
            }
            
            // Navigate to the step containing the error
            const stepElement = field.closest('.form-step');
            if (stepElement) {
                const stepNumber = parseInt(stepElement.dataset.step);
                
                if (stepNumber !== editCurrentStep) {
                    // Go to the step with error
                    while (editCurrentStep > stepNumber) {
                        editPreviousStep();
                    }
                    while (editCurrentStep < stepNumber) {
                        // Skip validation for navigation to error step
                        const tempValidation = window.validateEditCurrentStep;
                        window.validateEditCurrentStep = () => true;
                        editNextStep();
                        window.validateEditCurrentStep = tempValidation;
                    }
                }
            }
        } else {
            console.log('Could not find edit field for error:', fieldName);
        }
    });
}

// Password strength for edit form
document.addEventListener('DOMContentLoaded', function() {
    const editPasswordField = document.getElementById('edit_password');
    if (editPasswordField) {
        editPasswordField.addEventListener('input', function() {
            const password = this.value;
            const strengthElement = this.parentElement.querySelector('.password-strength');
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
        });
    }
});

// Close edit modal handlers
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (document.getElementById('editDriverModal').classList.contains('show')) {
            closeEditDriverModal();
        }
    }
});

window.addEventListener('click', function(e) {
    const editModal = document.getElementById('editDriverModal');
    if (e.target === editModal) {
        closeEditDriverModal();
    }
});
</script>
@endpush