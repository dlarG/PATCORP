<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileCategory;
use App\Models\FileAccessLog;
use App\Models\SystemLog;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $analytics = $this->getAnalyticsData();
        return view('analytics.analytics', compact('analytics'));
    }

    public function getAnalyticsData()
    {
        return [
            'overview' => $this->getOverviewStats(),
            'file_stats' => $this->getFileStats(),
            'user_activity' => $this->getUserActivityStats(),
            'category_distribution' => $this->getCategoryDistribution(),
            'monthly_uploads' => $this->getMonthlyUploads(),
            'download_trends' => $this->getDownloadTrends(),
            'storage_usage' => $this->getStorageUsage(),
            'top_files' => $this->getTopFiles(),
            'user_rankings' => $this->getUserRankings(),
            'recent_activity' => $this->getRecentActivity()
        ];
    }

    private function getOverviewStats()
    {
        $totalFiles = File::count();
        $totalUsers = User::count();
        $totalDownloads = File::sum('download_count');
        $totalStorage = File::sum('file_size');

        $todayFiles = File::whereDate('created_at', today())->count();
        $todayDownloads = FileAccessLog::where('action', 'download')
            ->whereDate('created_at', today())->count();

        return [
            'total_files' => $totalFiles,
            'total_users' => $totalUsers,
            'total_downloads' => $totalDownloads,
            'total_storage' => $totalStorage,
            'today_files' => $todayFiles,
            'today_downloads' => $todayDownloads,
            'avg_file_size' => $totalFiles > 0 ? $totalStorage / $totalFiles : 0,
            'files_per_user' => $totalUsers > 0 ? $totalFiles / $totalUsers : 0
        ];
    }

    private function getFileStats()
    {
        return [
            'by_type' => File::select(
                    DB::raw('SUBSTRING_INDEX(file_type, "/", 1) as type'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(file_size) as total_size')
                )
                ->groupBy('type')
                ->orderBy('count', 'desc')
                ->get(),
            'by_extension' => File::select(
                    DB::raw('SUBSTRING_INDEX(filename, ".", -1) as extension'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('extension')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'size_distribution' => [
                'small' => File::where('file_size', '<', 1024*1024)->count(), // < 1MB
                'medium' => File::whereBetween('file_size', [1024*1024, 10*1024*1024])->count(), // 1-10MB
                'large' => File::where('file_size', '>', 10*1024*1024)->count() // > 10MB
            ]
        ];
    }

    private function getUserActivityStats()
    {
        $last30Days = now()->subDays(30);
        
        return [
            'active_users' => User::whereHas('uploadedFiles', function($query) use ($last30Days) {
                $query->where('created_at', '>=', $last30Days);
            })->count(),
            'daily_activity' => SystemLog::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(DISTINCT user_id) as active_users'),
                    DB::raw('COUNT(*) as total_actions')
                )
                ->where('created_at', '>=', $last30Days)
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_actions' => SystemLog::select('action', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', $last30Days)
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
        ];
    }

    private function getCategoryDistribution()
    {
        return FileCategory::leftJoin('files', 'file_categories.id', '=', 'files.category_id')
            ->select(
                'file_categories.category_name',
                DB::raw('COUNT(files.id) as file_count'),
                DB::raw('SUM(files.file_size) as total_size')
            )
            ->groupBy('file_categories.id', 'file_categories.category_name')
            ->orderBy('file_count', 'desc')
            ->get();
    }

    private function getMonthlyUploads()
    {
        return File::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(file_size) as total_size')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $item->month_name = Carbon::create($item->year, $item->month)->format('M Y');
                return $item;
            });
    }

    private function getDownloadTrends()
    {
        return FileAccessLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as downloads')
            )
            ->where('action', 'download')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getStorageUsage()
    {
        $totalStorage = File::sum('file_size');
        $storageLimit = 10 * 1024 * 1024 * 1024; // 10GB limit (adjust as needed)
        
        return [
            'used' => $totalStorage,
            'limit' => $storageLimit,
            'percentage' => $storageLimit > 0 ? ($totalStorage / $storageLimit) * 100 : 0,
            'by_user' => User::leftJoin('files', 'users.id', '=', 'files.uploaded_by')
                ->select(
                    'users.first_name',
                    'users.last_name',
                    DB::raw('COUNT(files.id) as file_count'),
                    DB::raw('SUM(files.file_size) as storage_used')
                )
                ->groupBy('users.id', 'users.first_name', 'users.last_name')
                ->orderBy('storage_used', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    private function getTopFiles()
    {
        return File::with(['category', 'uploadedBy'])
            ->select('*')
            ->orderBy('download_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getUserRankings()
    {
        return [
            'top_uploaders' => User::leftJoin('files', 'users.id', '=', 'files.uploaded_by')
                ->select(
                    'users.first_name',
                    'users.last_name',
                    DB::raw('COUNT(files.id) as upload_count')
                )
                ->groupBy('users.id', 'users.first_name', 'users.last_name')
                ->orderBy('upload_count', 'desc')
                ->limit(10)
                ->get(),
            'most_active' => User::leftJoin('system_logs', 'users.id', '=', 'system_logs.user_id')
                ->select(
                    'users.first_name',
                    'users.last_name',
                    DB::raw('COUNT(system_logs.id) as activity_count')
                )
                ->where('system_logs.created_at', '>=', now()->subDays(30))
                ->groupBy('users.id', 'users.first_name', 'users.last_name')
                ->orderBy('activity_count', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    private function getRecentActivity()
    {
        return SystemLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'overview');
        $analytics = $this->getAnalyticsData();
        
        $filename = "analytics_{$type}_" . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($analytics, $type) {
            $file = fopen('php://output', 'w');
            
            switch($type) {
                case 'overview':
                    fputcsv($file, ['Metric', 'Value']);
                    foreach($analytics['overview'] as $key => $value) {
                        fputcsv($file, [ucfirst(str_replace('_', ' ', $key)), $value]);
                    }
                    break;
                    
                case 'files':
                    fputcsv($file, ['File Type', 'Count', 'Total Size']);
                    foreach($analytics['file_stats']['by_type'] as $stat) {
                        fputcsv($file, [$stat->type, $stat->count, $stat->total_size]);
                    }
                    break;
                    
                case 'users':
                    fputcsv($file, ['User', 'Upload Count']);
                    foreach($analytics['user_rankings']['top_uploaders'] as $user) {
                        fputcsv($file, [
                            $user->first_name . ' ' . $user->last_name,
                            $user->upload_count
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}