<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use App\Models\FileAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'system');
        $perPage = $request->get('per_page', 20); // Default 20 per page
        
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        // System Logs
        $systemLogsQuery = SystemLog::with('user')
            ->orderBy('created_at', 'desc');
            
        // File Access Logs
        $fileLogsQuery = FileAccessLog::with(['file', 'user'])
            ->orderBy('created_at', 'desc');
            
        // Apply filters if provided
        if ($request->filled('date_from')) {
            $systemLogsQuery->whereDate('created_at', '>=', $request->date_from);
            $fileLogsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $systemLogsQuery->whereDate('created_at', '<=', $request->date_to);
            $fileLogsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('user_id')) {
            $systemLogsQuery->where('user_id', $request->user_id);
            $fileLogsQuery->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action') && $activeTab === 'file') {
            $fileLogsQuery->where('action', $request->action);
        }
        
        if ($request->filled('module') && $activeTab === 'system') {
            $systemLogsQuery->where('module', $request->module);
        }
        
        // Paginate results
        $systemLogs = $systemLogsQuery->paginate($perPage, ['*'], 'system_page')
            ->appends(['tab' => $activeTab])
            ->appends($request->except('page', 'system_page'));
        
        $fileLogs = $fileLogsQuery->paginate($perPage, ['*'], 'file_page')
            ->appends(['tab' => $activeTab])
            ->appends($request->except('page', 'file_page'));
        
        // Get filter options
        $users = \App\Models\User::select('id', 'first_name', 'last_name')->get();
        $modules = SystemLog::distinct()->pluck('module')->filter();
        $actions = FileAccessLog::distinct()->pluck('action')->filter();
        
        // Get statistics
        $stats = $this->getLogStatistics();
        
        return view('system.logs', compact(
            'systemLogs', 
            'fileLogs', 
            'users', 
            'modules', 
            'actions', 
            'activeTab', 
            'stats'
        ));
    }
    
    public function export(Request $request)
    {
        $type = $request->get('type', 'system');
        
        if ($type === 'system') {
            $logs = SystemLog::with('user')
                ->when($request->filled('date_from'), function($q) use ($request) {
                    return $q->whereDate('created_at', '>=', $request->date_from);
                })
                ->when($request->filled('date_to'), function($q) use ($request) {
                    return $q->whereDate('created_at', '<=', $request->date_to);
                })
                ->orderBy('created_at', 'desc')
                ->get();
                
            $filename = 'system_logs_' . date('Y-m-d_H-i-s') . '.csv';
            
        } else {
            $logs = FileAccessLog::with(['file', 'user'])
                ->when($request->filled('date_from'), function($q) use ($request) {
                    return $q->whereDate('created_at', '>=', $request->date_from);
                })
                ->when($request->filled('date_to'), function($q) use ($request) {
                    return $q->whereDate('created_at', '<=', $request->date_to);
                })
                ->orderBy('created_at', 'desc')
                ->get();
                
            $filename = 'file_logs_' . date('Y-m-d_H-i-s') . '.csv';
        }
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($logs, $type) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'system') {
                fputcsv($file, ['Date/Time', 'User', 'Action', 'Module', 'Description', 'IP Address']);
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'Unknown',
                        $log->action,
                        $log->module,
                        $log->description,
                        $log->ip_address
                    ]);
                }
            } else {
                fputcsv($file, ['Date/Time', 'User', 'File', 'Action', 'IP Address', 'User Agent']);
                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'Unknown',
                        $log->file ? $log->file->original_filename : 'Deleted File',
                        $log->action,
                        $log->ip_address,
                        $log->user_agent
                    ]);
                }
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    public function clear(Request $request)
    {
        $type = $request->get('type', 'system');
        $days = $request->get('days', 30);
        
        $cutoffDate = now()->subDays($days);
        
        try {
            if ($type === 'system') {
                $deleted = SystemLog::where('created_at', '<', $cutoffDate)->delete();
                $message = "Deleted {$deleted} system log entries older than {$days} days.";
            } else {
                $deleted = FileAccessLog::where('created_at', '<', $cutoffDate)->delete();
                $message = "Deleted {$deleted} file access log entries older than {$days} days.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear logs: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getLogStatistics()
    {
        return [
            'system_logs_count' => SystemLog::count(),
            'file_logs_count' => FileAccessLog::count(),
            'today_system_logs' => SystemLog::whereDate('created_at', today())->count(),
            'today_file_logs' => FileAccessLog::whereDate('created_at', today())->count(),
            'recent_system_actions' => SystemLog::select('action', DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'recent_file_actions' => FileAccessLog::select('action', DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'top_users_system' => SystemLog::select('user_id', DB::raw('count(*) as count'))
                ->with('user')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'top_users_files' => FileAccessLog::select('user_id', DB::raw('count(*) as count'))
                ->with('user')
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }
}   