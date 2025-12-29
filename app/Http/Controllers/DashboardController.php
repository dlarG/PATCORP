<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Driver;
use App\Models\File;
use App\Models\PaymentRecord;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_drivers' => Driver::count(),
            'active_drivers' => Driver::where('status', 'active')->count(),
            'unpaid_drivers' => Driver::where('payment_status', 'unpaid')->count(),
            'total_files' => File::where('uploaded_by', Auth::id())->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    public function driverDashboard()
    {
        $user = auth()->user();
        $driver = Driver::where('user_id', $user->id)->first();
        
        if (!$driver) {
            return redirect()->route('login')->withErrors(['error' => 'Driver record not found.']);
        }
        
        $files = File::where('driver_id', $driver->id)->get();
        $payments = PaymentRecord::where('driver_id', $driver->id)->latest()->take(5)->get();

        return view('dashboard.driver', compact('driver', 'files', 'payments'));
    }
}