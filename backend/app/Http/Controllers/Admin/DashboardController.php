<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index()
    {
        // Get counts
        $userCount = User::count();
        $itemCount = Item::count();
        $reportCount = Report::count();
        
        // Get counts by status
        $activeUsers = User::where('status', 'active')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();
        $bannedUsers = User::where('status', 'banned')->count();
        
        $activeItems = Item::where('status', 'active')->count();
        $archivedItems = Item::where('status', 'archived')->count();
        $reportedItems = Item::where('status', 'reported')->count();
        
        $pendingReports = Report::where('status', 'pending')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();
        $dismissedReports = Report::where('status', 'dismissed')->count();
        
        // Get counts by type
        $lostItems = Item::where('type', 'lost')->count();
        $foundItems = Item::where('type', 'found')->count();
        
        $userReports = Report::where('reportable_type', User::class)->count();
        $itemReports = Report::where('reportable_type', Item::class)->count();
        
        // Get recent activity
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentItems = Item::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        $recentReports = Report::with('reporter')->orderBy('created_at', 'desc')->take(5)->get();
        
        // Get user registration trend (last 7 days)
        $userTrend = $this->getUserRegistrationTrend();
        
        // Get item creation trend (last 7 days)
        $itemTrend = $this->getItemCreationTrend();
        
        return response()->json([
            'counts' => [
                'users' => $userCount,
                'items' => $itemCount,
                'reports' => $reportCount,
            ],
            'users' => [
                'active' => $activeUsers,
                'suspended' => $suspendedUsers,
                'banned' => $bannedUsers,
                'recent' => $recentUsers,
                'trend' => $userTrend,
            ],
            'items' => [
                'active' => $activeItems,
                'archived' => $archivedItems,
                'reported' => $reportedItems,
                'lost' => $lostItems,
                'found' => $foundItems,
                'recent' => $recentItems,
                'trend' => $itemTrend,
            ],
            'reports' => [
                'pending' => $pendingReports,
                'resolved' => $resolvedReports,
                'dismissed' => $dismissedReports,
                'userReports' => $userReports,
                'itemReports' => $itemReports,
                'recent' => $recentReports,
            ],
        ]);
    }
    
    /**
     * Get user registration trend for the last 7 days
     */
    private function getUserRegistrationTrend()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $users = User::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Create an array with all dates in the range
        $trend = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $trend[$formattedDate] = 0;
        }
        
        // Fill in the actual counts
        foreach ($users as $user) {
            $trend[$user->date] = $user->count;
        }
        
        // Format for response
        $result = [];
        foreach ($trend as $date => $count) {
            $result[] = [
                'date' => $date,
                'count' => $count,
            ];
        }
        
        return $result;
    }
    
    /**
     * Get item creation trend for the last 7 days
     */
    private function getItemCreationTrend()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $items = Item::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), 'type')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();
        
        // Create an array with all dates in the range
        $trend = [];
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $trend[$formattedDate] = [
                'lost' => 0,
                'found' => 0,
                'total' => 0,
            ];
        }
        
        // Fill in the actual counts
        foreach ($items as $item) {
            $trend[$item->date][$item->type] = $item->count;
            $trend[$item->date]['total'] += $item->count;
        }
        
        // Format for response
        $result = [];
        foreach ($trend as $date => $counts) {
            $result[] = [
                'date' => $date,
                'lost' => $counts['lost'],
                'found' => $counts['found'],
                'total' => $counts['total'],
            ];
        }
        
        return $result;
    }
}
