<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        // Determine if login is email or username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        // Find user by email or username
        $user = User::where($loginType, $request->login)
                    ->where('is_active', true)
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Manually log in the user
            Auth::login($user);
            
            // Update last login
            $user->last_login = now();
            $user->save();

            // Log system access
            SystemLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'module' => 'system',
                'description' => 'User logged in successfully',
                'ip_address' => $request->ip()
            ]);

            // Redirect based on user type
            if ($user->user_type === 'admin') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('driver.dashboard');
            }
        }

        return back()->withErrors([
            'login' => 'Invalid credentials or account inactive.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|max:50|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'first_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
            'phone' => 'nullable|max:20|regex:/^[\d\s\-\+\(\)]+$/',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password_hash' => Hash::make($request->password),
            'email' => $request->email,
            'user_type' => 'driver',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'is_active' => true
        ]);

        // Create driver record for all registered users
        Driver::create([
            'user_id' => $user->id,
            'driver_id' => 'DRV-' . strtoupper(uniqid()),
            'license_number' => 'TEMP-' . time(),
            'license_expiry' => now()->addYears(5),
            'vehicle_type' => 'car',
            'vehicle_plate' => 'TEMP',
            'hire_date' => now(),
            'status' => 'active'
        ]);

        // Log registration
        SystemLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'module' => 'user',
            'description' => 'New user registered as driver',
            'ip_address' => $request->ip()
        ]);

        Auth::login($user);

        return redirect()->route('driver.dashboard');
    }

    public function logout(Request $request)
    {
        // Log logout
        if (Auth::check()) {
            SystemLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'module' => 'system',
                'description' => 'User logged out',
                'ip_address' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}