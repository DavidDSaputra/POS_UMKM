<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'items']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('method', $request->payment_method);
            });
        }

        // Search by invoice number
        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%");
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Summary statistics
        $totalRevenue = $query->sum('grand_total');
        $totalOrders = $query->count();

        return view('admin.transactions.index', compact('orders', 'totalRevenue', 'totalOrders'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'payment', 'items.product']);
        return view('admin.transactions.show', compact('order'));
    }
}
