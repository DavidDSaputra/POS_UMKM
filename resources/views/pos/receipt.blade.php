<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $order->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            padding: 10px;
            max-width: 300px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .store-name {
            font-size: 16px;
            font-weight: bold;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .items {
            margin-bottom: 10px;
        }

        .item {
            margin-bottom: 5px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-row {
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="store-name">WARUNG POS</div>
        <div>Jl. Contoh No. 123</div>
        <div>Telp: 0812-3456-7890</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span>No:</span>
            <span>{{ $order->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span>Tanggal:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span>Kasir:</span>
            <span>{{ $order->user->name }}</span>
        </div>
        <div class="info-row">
            <span>Tipe:</span>
            <span>{{ $order->type === 'dine_in' ? 'Dine In' : 'Takeaway' }}{{ $order->table_number ? ' (Meja ' . $order->table_number . ')' : '' }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <div class="items">
        @foreach($order->items as $item)
            <div class="item">
                <div class="item-name">{{ $item->product_name }}</div>
                <div class="item-detail">
                    <span>{{ $item->quantity }} x Rp {{ number_format($item->product_price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="divider"></div>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($order->discount_amount > 0)
            <div class="summary-row">
                <span>Diskon{{ $order->discount_type === 'percent' ? ' (' . $order->discount_value . '%)' : '' }}</span>
                <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
        @endif
        @if($order->tax_amount > 0)
            <div class="summary-row">
                <span>Pajak ({{ $order->tax_percent }}%)</span>
                <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
            </div>
        @endif
        @if($order->service_amount > 0)
            <div class="summary-row">
                <span>Service ({{ $order->service_percent }}%)</span>
                <span>Rp {{ number_format($order->service_amount, 0, ',', '.') }}</span>
            </div>
        @endif
        <div class="summary-row total-row">
            <span>TOTAL</span>
            <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="divider"></div>

    @if($order->payment)
        <div class="summary">
            <div class="summary-row">
                <span>Metode</span>
                <span>{{ strtoupper($order->payment->method) }}</span>
            </div>
            <div class="summary-row">
                <span>Bayar</span>
                <span>Rp {{ number_format($order->payment->amount_paid, 0, ',', '.') }}</span>
            </div>
            @if($order->payment->change_amount > 0)
                <div class="summary-row">
                    <span>Kembali</span>
                    <span>Rp {{ number_format($order->payment->change_amount, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>
    @endif

    <div class="footer">
        <div>Terima Kasih</div>
        <div>Selamat Menikmati!</div>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">
            ✕ Tutup
        </button>
    </div>
</body>

</html>