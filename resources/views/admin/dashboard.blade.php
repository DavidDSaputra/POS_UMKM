@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card primary">
                <div class="stat-content">
                    <h3>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    <p>Omzet Hari Ini</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card success">
                <div class="stat-content">
                    <h3>{{ $todayOrders }}</h3>
                    <p>Transaksi Hari Ini</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card warning">
                <div class="stat-content">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Produk</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card danger">
                <div class="stat-content">
                    <h3>{{ $activeProducts }}</h3>
                    <p>Produk Aktif</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-chart-line me-2 text-primary"></i>Omzet 7 Hari Terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-2 text-primary"></i>Metode Pembayaran
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="paymentChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Row -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-fire me-2 text-danger"></i>Produk Terlaris Hari Ini
                </div>
                <div class="card-body p-0">
                    @if($bestSellers->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($bestSellers as $index => $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <span
                                            class="badge bg-{{ $index < 3 ? 'primary' : 'secondary' }} me-2">{{ $index + 1 }}</span>
                                        {{ $product->name }}
                                    </span>
                                    <span class="badge bg-success">{{ $product->total_qty }} terjual</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada data</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-clock me-2 text-warning"></i>Jam Ramai Hari Ini
                </div>
                <div class="card-body p-0">
                    @if($peakHours->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($peakHours as $peak)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-clock text-muted me-2"></i>
                                        {{ str_pad($peak->hour, 2, '0', STR_PAD_LEFT) }}:00 -
                                        {{ str_pad($peak->hour + 1, 2, '0', STR_PAD_LEFT) }}:00
                                    </span>
                                    <span class="badge bg-warning text-dark">{{ $peak->count }} transaksi</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada data</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-receipt me-2 text-info"></i>Transaksi Terakhir</span>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @if($recentOrders->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($recentOrders->take(5) as $order)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">{{ $order->invoice_number }}</span>
                                        <span class="text-success">{{ $order->formatted_grand_total }}</span>
                                    </div>
                                    <small class="text-muted">
                                        {{ $order->created_at->format('H:i') }} • {{ $order->payment->method ?? '-' }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($last7Days->pluck('date')) !!},
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: {!! json_encode($last7Days->pluck('revenue')) !!},
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6f42c1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Payment Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentData = {!! json_encode($paymentMethods) !!};

        if (paymentData.length > 0) {
            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: paymentData.map(p => p.method.toUpperCase()),
                    datasets: [{
                        data: paymentData.map(p => p.count),
                        backgroundColor: ['#6f42c1', '#00d25b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        } else {
            document.getElementById('paymentChart').parentElement.innerHTML =
                '<div class="text-center text-muted"><i class="fas fa-chart-pie fa-3x mb-2"></i><p>Belum ada data</p></div>';
        }
    </script>
@endpush