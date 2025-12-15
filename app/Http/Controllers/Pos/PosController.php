<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\OrderCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    protected OrderCalculationService $calculationService;

    public function __construct(OrderCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Display the POS screen.
     */
    public function index(Request $request)
    {
        $categories = Category::active()->withCount('products')->orderBy('name')->get();

        $query = Product::active()->with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->get();

        return view('pos.index', compact('categories', 'products'));
    }

    /**
     * Get products as JSON for AJAX.
     */
    public function products(Request $request)
    {
        $query = Product::active()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'formatted_price' => $product->formatted_price,
                    'stock' => $product->stock,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'category' => $product->category->name,
                ];
            }),
        ]);
    }

    /**
     * Calculate order totals.
     */
    public function calculate(Request $request)
    {
        $items = $request->input('items', []);
        $options = [
            'discount_type' => $request->input('discount_type'),
            'discount_value' => $request->input('discount_value', 0),
            'tax_percent' => $request->input('tax_percent', 0),
            'service_percent' => $request->input('service_percent', 0),
        ];

        $calculation = $this->calculationService->calculate($items, $options);

        return response()->json($calculation);
    }

    /**
     * Process checkout.
     */
    public function checkout(CheckoutRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Prepare items with product data
            $items = [];
            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock if tracking enabled
                if ($product->hasStockTracking() && $product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi!");
                }

                $itemTotal = $product->price * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal,
                ];
                $subtotal += $itemTotal;
            }

            // Calculate totals
            $calculation = $this->calculationService->calculate(
                array_map(fn($item) => [
                    'price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                ], $items),
                [
                    'discount_type' => $validated['discount_type'] ?? null,
                    'discount_value' => $validated['discount_value'] ?? 0,
                    'tax_percent' => $validated['tax_percent'] ?? 0,
                    'service_percent' => $validated['service_percent'] ?? 0,
                ]
            );

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'table_number' => $validated['table_number'] ?? null,
                'subtotal' => $calculation['subtotal'],
                'discount_type' => $calculation['discount_type'],
                'discount_value' => $calculation['discount_value'],
                'discount_amount' => $calculation['discount_amount'],
                'tax_percent' => $calculation['tax_percent'],
                'tax_amount' => $calculation['tax_amount'],
                'service_percent' => $calculation['service_percent'],
                'service_amount' => $calculation['service_amount'],
                'grand_total' => $calculation['grand_total'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
            ]);

            // Create order items and update stock
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);

                // Update stock if tracking enabled
                if ($item['product']->hasStockTracking()) {
                    $item['product']->decrement('stock', $item['quantity']);
                }
            }

            // Create payment
            $amountPaid = $validated['payment_method'] === 'cash'
                ? $validated['amount_paid']
                : $calculation['grand_total'];

            $changeAmount = $this->calculationService->calculateChange(
                $calculation['grand_total'],
                $amountPaid
            );

            Payment::create([
                'order_id' => $order->id,
                'method' => $validated['payment_method'],
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'status' => 'paid',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'order' => [
                    'id' => $order->id,
                    'invoice_number' => $order->invoice_number,
                    'grand_total' => $order->grand_total,
                    'formatted_grand_total' => $order->formatted_grand_total,
                    'payment_method' => $validated['payment_method'],
                    'amount_paid' => $amountPaid,
                    'change_amount' => $changeAmount,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get order receipt.
     */
    public function receipt(Order $order)
    {
        $order->load(['items', 'payment', 'user']);
        return view('pos.receipt', compact('order'));
    }
}
