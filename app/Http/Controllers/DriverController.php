<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    /**
     * Display a listing of all drivers
     */
    public function index()
    {
        $drivers = User::with('driver')
            ->where('user_type', 'driver')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new driver
     */
    public function create()
    {
        return view('drivers.create');
    }

    /**
     * Store a newly created driver
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'username' => 'required|unique:users|max:50|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|email|unique:users|max:100',
                'password' => 'required|min:8|confirmed',
                'first_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
                'phone' => 'required|max:20',
                'address' => 'nullable|string|max:500',
                'license_number' => 'required|unique:drivers|max:50',
                'license_expiry' => 'required|date|after:today',
                'vehicle_type' => 'required|in:car,truck,motorcycle,van',
                'vehicle_plate' => 'required|max:20',
                'emergency_contact' => 'nullable|max:50',
                'emergency_phone' => 'nullable|max:20',
                'hire_date' => 'required|date',
                'monthly_salary' => 'nullable|numeric|min:0|max:999999.99',
            ]);

            // Start database transaction
            DB::beginTransaction();

            // Create user record
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'email' => $validated['email'],
                'user_type' => 'driver',
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'is_active' => true
            ]);

            // Create driver record
            $driver = Driver::create([
                'user_id' => $user->id,
                'driver_id' => 'DRV-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
                'vehicle_type' => $validated['vehicle_type'],
                'vehicle_plate' => strtoupper($validated['vehicle_plate']),
                'address' => $validated['address'] ?? null,
                'emergency_contact' => $validated['emergency_contact'] ?? null,
                'emergency_phone' => $validated['emergency_phone'] ?? null,
                'hire_date' => $validated['hire_date'],
                'status' => 'active',
                'payment_status' => 'unpaid',
                'monthly_salary' => $validated['monthly_salary'] ?? null,
            ]);

            // Commit the transaction
            DB::commit();

            // For AJAX requests, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Driver created successfully!',
                    'driver' => $user->load('driver')
                ]);
            }

            // For regular form submissions, redirect
            return redirect()->route('drivers.index')->with('success', 'Driver created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error for debugging
            Log::error('Driver creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while creating the driver. Please try again.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while creating the driver. Please try again.')->withInput();
        }
    }

    /**
     * Display the specified driver
     */
    public function show(User $driver)
    {
        $driver->load('driver');
        return view('drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified driver
     */
    public function edit(User $driver)
    {
        $driver->load('driver');
        return view('drivers.edit', compact('driver'));
    }

    /**
     * Update the specified driver
     */
    public function update(Request $request, User $driver)
    {
        $request->validate([
            'username' => 'required|max:50|regex:/^[a-zA-Z0-9_]+$/|unique:users,username,' . $driver->id,
            'email' => 'required|email|max:100|unique:users,email,' . $driver->id,
            'first_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'nullable|max:20|regex:/^[\d\s\-\+\(\)]+$/',
            'license_number' => 'required|max:50|unique:drivers,license_number,' . $driver->driver->id,
            'license_expiry' => 'required|date|after:today',
            'vehicle_type' => 'required|in:car,truck,motorcycle,van',
            'vehicle_plate' => 'required|max:20',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Update user
        $driver->update([
            'username' => $request->username,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'is_active' => $request->status === 'active'
        ]);

        // Update driver record
        $driver->driver->update([
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plate' => $request->vehicle_plate,
            'hire_date' => $request->hire_date,
            'status' => $request->status,
        ]);

        // Log the action
        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_driver',
            'module' => 'driver',
            'description' => "Updated driver: {$driver->first_name} {$driver->last_name}",
            'ip_address' => $request->ip()
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully!');
    }

    /**
     * Toggle payment status
     */
    public function togglePaymentStatus(Request $request, User $driver)
    {
        $currentStatus = $driver->driver->payment_status;
        $newStatus = $currentStatus === 'paid' ? 'unpaid' : 'paid';
        
        $driver->driver->update(['payment_status' => $newStatus]);

        // Log the action
        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'toggle_payment_status',
            'module' => 'driver',
            'description' => "Changed payment status for {$driver->first_name} {$driver->last_name} from {$currentStatus} to {$newStatus}",
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => "Payment status updated to {$newStatus}"
        ]);
    }

    /**
     * Remove the specified driver
     */
    public function destroy(User $driver)
    {
        $driverName = $driver->first_name . ' ' . $driver->last_name;
        
        // Delete driver record first (foreign key constraint)
        $driver->driver->delete();
        $driver->delete();

        // Log the action
        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_driver',
            'module' => 'driver',
            'description' => "Deleted driver: {$driverName}",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully!');
    }
}