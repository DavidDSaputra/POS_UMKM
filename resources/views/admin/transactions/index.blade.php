@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Riwayat Transaksi</h4>
            <p class="text-muted mb-0">Lihat semua transaksi yang sudah dilakukan</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="row g-3">
                <div class="col-md-2">
                    <input type="text" name="search" class="form-control" placeholder="No. Invoice"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                        placeholder="Dari Tanggal">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"
                        placeholder="Sampai Tanggal">
                </div>
                <div class="col-md-2">
                    <select name="payment_method" class="form-select">
                        <option value="">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    @if(request()->hasAny(['search', 'date_from', 'date_to', 'payment_method']))
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="card-body border-bottom bg-light">
            <div class="row text-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-primary">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                    <small class="text-muted">Total Pendapatan</small>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-0 text-success">{{ $totalOrders }}</h5>
                    <small class="text-muted">Total Transaksi</small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Tipe</th>
                            <th>Metode</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $order->invoice_number }}</span>
                                </td>
                                <td>
                                    {{ $order->created_at->format('d/m/Y') }}
                                    <br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    @if($order->type === 'dine_in')
                                        <span class="badge bg-info"><i class="fas fa-utensils me-1"></i>Dine In</span>
                                        @if($order->table_number)
                                            <br><small class="text-muted">Meja {{ $order->table_number }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-warning text-dark"><i
                                                class="fas fa-shopping-bag me-1"></i>Takeaway</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->payment)
                                        <span class="badge bg-{{ $order->payment->method === 'cash' ? 'success' : 'primary' }}">
                                            {{ strtoupper($order->payment->method) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-success">{{ $order->formatted_grand_total }}</td>
                                <td>
                                    @if($order->status === 'completed')
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Selesai</span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Batal</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $order) }}"
                                        class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Belum ada transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endsection