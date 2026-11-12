<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\Driver;
use App\Models\Staff;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Delivery;
use App\Models\SupportTicket;
use App\Models\RealEstateProperty;
use App\Models\PropertyInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        return view('pages.admin.dashboard', compact('stats'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            // User Statistics
            'total_users' => User::count(),
            'total_customers' => Customer::count(),
            'total_merchants' => Merchant::count(),
            'total_drivers' => Driver::count(),
            'total_staff' => Staff::count(),

            // Business Statistics
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'active')->count(),
            'out_of_stock_products' => Product::where('stock_quantity', '<=', 0)->count(),

            // Financial Statistics
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),

            // Delivery Statistics
            'active_deliveries' => Delivery::where('status', 'in_transit')->count(),
            'available_drivers' => Driver::where('status', 'available')->count(),

            // Support Statistics
            'open_tickets' => SupportTicket::where('status', 'open')->count(),
            'pending_tickets' => SupportTicket::where('status', 'pending')->count(),
            'resolved_tickets' => SupportTicket::where('status', 'resolved')->count(),

            // Real Estate Statistics
            'available_properties' => RealEstateProperty::where('status', 'available')->count(),
            'new_inquiries' => PropertyInquiry::where('status', 'new')->count(),
            'sold_properties' => RealEstateProperty::where('status', 'sold')->count(),

            // Recent Activity
            'recent_orders' => Order::with(['customer', 'orderItems'])
                ->latest()
                ->take(5)
                ->get(),

            // Sales Chart Data (last 7 days)
            'sales_chart_data' => $this->getSalesChartData(),

            // Growth Percentages
            'user_growth' => $this->calculateGrowthPercentage('users'),
            'order_growth' => $this->calculateGrowthPercentage('orders'),
            'revenue_growth' => $this->calculateRevenueGrowthPercentage(),
        ];
    }

    /**
     * Get sales chart data for the last 7 days
     */
    private function getSalesChartData()
    {
        $days = ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Payment::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('amount');

            $data[] = [
                'day' => $days[($date->dayOfWeek + 1) % 7], // Adjust for Arabic week start
                'revenue' => $revenue,
                'percentage' => 0 // Will be calculated based on max value
            ];
        }

        // Calculate percentages based on max value
        $maxRevenue = collect($data)->max('revenue');
        if ($maxRevenue > 0) {
            foreach ($data as &$item) {
                $item['percentage'] = ($item['revenue'] / $maxRevenue) * 100;
            }
        }

        return $data;
    }

    /**
     * Calculate growth percentage for a given metric
     */
    private function calculateGrowthPercentage($metric)
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

        switch ($metric) {
            case 'users':
                $current = User::whereMonth('created_at', $currentMonth)->count();
                $previous = User::whereMonth('created_at', $lastMonth)->count();
                break;

            case 'orders':
                $current = Order::whereMonth('created_at', $currentMonth)->count();
                $previous = Order::whereMonth('created_at', $lastMonth)->count();
                break;

            default:
                return 0;
        }

        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Calculate revenue growth percentage
     */
    private function calculateRevenueGrowthPercentage()
    {
        $currentMonth = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $lastMonth = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('amount');

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }
}
