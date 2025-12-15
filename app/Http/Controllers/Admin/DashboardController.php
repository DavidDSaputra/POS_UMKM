<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Today's statistics
        $todayOrders = Order::today()->completed()->count();
        $todayRevenue = Order::today()->completed()->sum('grand_total');

        // Best selling products (top 5)
        $bestSellers = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', today())
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Peak hours (orders per hour today) - compatible with MySQL and SQLite
        $driver = DB::getDriverName();
        $hourExpression = $driver === 'sqlite'
            ? "strftime('%H', created_at)"
            : 'HOUR(created_at)';

        $peakHours = Order::today()
            ->completed()
            ->select(DB::raw("$hourExpression as hour"), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw($hourExpression))
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        // Last 7 days revenue for chart
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->completed()
                ->sum('grand_total');
            $last7Days->push([
                'date' => $date->format('d M'),
                'revenue' => $revenue,
            ]);
        }

        // Payment methods distribution
        $paymentMethods = Payment::join('orders', 'payments.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', today())
            ->select('payments.method', DB::raw('COUNT(*) as count'))
            ->groupBy('payments.method')
            ->get();

        // Recent orders
        $recentOrders = Order::with(['user', 'payment'])
            ->completed()
            ->latest()
            ->limit(10)
            ->get();

        // Total products and active
        $totalProducts = Product::count();
        $activeProducts = Product::active()->count();

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'bestSellers',
            'peakHours',
            'last7Days',
            'paymentMethods',
            'recentOrders',
            'totalProducts',
            'activeProducts'
        ));
    }
}
