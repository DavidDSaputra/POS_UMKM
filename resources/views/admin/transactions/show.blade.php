@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.transactions.index') }}">Transaksi</a></li>
                <li class="breadcrumb-item active">{{ $order->invoice_number }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-cart me-2 text-primary"></i>Item Pesanan
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $item->product_name }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Pesanan
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted">No. Invoice</td>
                            <td class="text-end fw-semibold">{{ $order->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal</td>
                            <td class="text-end">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kasir</td>
                            <td class="text-end">{{ $order->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tipe</td>
                            <td class="text-end">
                                @if($order->type === 'dine_in')
                                    <span class="badge bg-info">Dine In</span>
                                    @if($order->table_number)
                                        <span class="text-muted">(Meja {{ $order->table_number }})</span>
                                    @endif
                                @else
                                    <span class="badge bg-warning text-dark">Takeaway</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td class="text-end">
                                @if($order->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calculator me-2 text-primary"></i>Ringkasan Pembayaran
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Subtotal</td>
                            <td class="text-end">{{ $order->formatted_subtotal }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td class="text-muted">
                                    Diskon
                                    @if($order->discount_type === 'percent')
                                        ({{ $order->discount_value }}%)
                                    @endif
                                </td>
                                <td class="text-end text-danger">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                        @if($order->tax_amount > 0)
                            <tr>
                                <td class="text-muted">Pajak ({{ $order->tax_percent }}%)</td>
                                <td class="text-end">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if($order->service_amount > 0)
                            <tr>
                                <td class="text-muted">Service ({{ $order->service_percent }}%)</td>
                                <td class="text-end">Rp {{ number_format($order->service_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="border-top">
                            <td class="fw-bold">Grand Total</td>
                            <td class="text-end fw-bold text-success fs-5">{{ $order->formatted_grand_total }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Payment Info -->
            @if($order->payment)
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-credit-card me-2 text-primary"></i>Pembayaran
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Metode</td>
                                <td class="text-end">
                                    <span
                                        class="badge bg-{{ $order->payment->method === 'cash' ? 'success' : 'primary' }} fs-6">
                                        {{ strtoupper($order->payment->method) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dibayar</td>
                                <td class="text-end">Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</td>
                            </tr>
                            @if($order->payment->method === 'cash' && $order->payment->change_amount > 0)
                                <tr>
                                    <td class="text-muted">Kembalian</td>
                                    <td class="text-end text-info">Rp
                                        {{ number_format($order->payment->change_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Status</td>
                                <td class="text-end">
                                    @if($order->payment->status === 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning">{{ ucfirst($order->payment->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('pos.receipt', $order) }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-print me-2"></i>Cetak Struk
        </a>
    </div>
@endsection