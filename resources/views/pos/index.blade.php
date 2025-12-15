@extends('layouts.pos')

@section('content')

{{-- CSS Styles --}}
    <style>
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            padding: 10px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 280px; /* Fixed height */
            overflow: hidden;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }

        .product-card.out-of-stock {
            opacity: 0.7;
        }

        .product-card.out-of-stock:hover {
            transform: none;
            cursor: not-allowed;
        }

        .product-image-container {
            height: 150px;
            width: 100%;
            position: relative;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-image-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #6c757d;
        }

        .product-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-name {
            font-weight: 600;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            line-height: 1.3;
            height: 36px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-details {
            margin-top: auto;
        }

        .product-price {
            font-weight: 700;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .product-stock-info {
            font-size: 12px;
            display: flex;
            align-items: center;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            z-index: 2;
        }

        .out-of-stock-badge {
            background-color: #dc3545;
            color: white;
        }

        .low-stock-badge {
            background-color: #ffc107;
            color: #212529;
        }

        .stock-available {
            color: #28a745;
        }

        .stock-low {
            color: #fd7e14;
        }

        .stock-unlimited {
            color: #17a2b8;
        }

        .product-stock-info i {
            margin-right: 4px;
            font-size: 11px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 10px;
            }

            .product-card {
                height: 250px;
            }

            .product-image-container {
                height: 130px;
            }

            .product-name {
                font-size: 13px;
                height: 32px;
            }

            .product-price {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            }

            .product-card {
                height: 230px;
            }

            .product-image-container {
                height: 120px;
            }
        }
    </style>

    <div class="pos-container">
        <!-- Left Panel - Cart -->
        <div class="cart-panel">
            <div class="cart-header">
                <h5><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
                <div class="cart-type-toggle">
                    <button type="button" class="btn btn-sm btn-light order-type-btn active" data-type="dine_in">
                        <i class="fas fa-utensils me-1"></i>Dine In
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-light order-type-btn" data-type="takeaway">
                        <i class="fas fa-shopping-bag me-1"></i>Takeaway
                    </button>
                </div>
            </div>

            <!-- Table Number (for Dine In) -->
            <div class="p-2 border-bottom table-input-wrapper">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fas fa-chair"></i></span>
                    <input type="text" class="form-control" id="tableNumber" placeholder="No. Meja (opsional)">
                </div>
            </div>

            <!-- Cart Items -->
            <div class="cart-items" id="cartItems">
                <div class="cart-empty" id="cartEmpty">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Keranjang kosong</p>
                    <small>Klik produk untuk menambahkan</small>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="cart-footer">
                <!-- Discount Toggle -->
                <div class="mb-2">
                    <a href="#" class="text-decoration-none small" data-bs-toggle="collapse"
                        data-bs-target="#discountOptions">
                        <i class="fas fa-tag me-1"></i>Tambah Diskon/Pajak
                    </a>
                </div>
                <div class="collapse mb-3" id="discountOptions">
                    <div class="row g-2">
                        <div class="col-5">
                            <select class="form-select form-select-sm" id="discountType">
                                <option value="">Tanpa Diskon</option>
                                <option value="percent">Diskon %</option>
                                <option value="nominal">Diskon Rp</option>
                            </select>
                        </div>
                        <div class="col-7">
                            <input type="number" class="form-control form-control-sm" id="discountValue" placeholder="Nilai"
                                min="0">
                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Pajak</span>
                                <input type="number" class="form-control" id="taxPercent" value="0" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Service</span>
                                <input type="number" class="form-control" id="servicePercent" value="0" min="0" max="100">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cart-summary">
                    <div class="cart-summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="cart-summary-row discount" id="discountRow" style="display: none;">
                        <span>Diskon</span>
                        <span id="discountAmount">- Rp 0</span>
                    </div>
                    <div class="cart-summary-row" id="taxRow" style="display: none;">
                        <span>Pajak</span>
                        <span id="taxAmount">Rp 0</span>
                    </div>
                    <div class="cart-summary-row" id="serviceRow" style="display: none;">
                        <span>Service</span>
                        <span id="serviceAmount">Rp 0</span>
                    </div>
                    <div class="cart-summary-row total">
                        <span>TOTAL</span>
                        <span id="grandTotal">Rp 0</span>
                    </div>
                </div>

                <div class="cart-actions">
                    <button type="button" class="btn btn-outline-danger" id="clearCart">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button type="button" class="btn btn-success" id="checkoutBtn" data-bs-toggle="modal"
                        data-bs-target="#checkoutModal" disabled>
                        <i class="fas fa-credit-card me-2"></i>Bayar
                        <span class="keyboard-hint">F2</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Panel - Products -->
        <div class="products-panel">
            <div class="products-header">
                <div class="products-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari produk... (F3)" autofocus>
                </div>
                <div class="products-categories" id="categoriesFilter">
                    <button type="button" class="category-btn active" data-category="">Semua</button>
                    @foreach($categories as $category)
                        <button type="button" class="category-btn" data-category="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
                <div class="user-menu">
                    @if(auth()->user()->isOwner())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-cog"></i>
                        </a>
                    @endif
                    <div class="dropdown">
                        <a href="#" class="user-avatar dropdown-toggle" data-bs-toggle="dropdown"
                            style="text-decoration:none">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text fw-bold">{{ auth()->user()->name }}</span></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="products-grid" id="productsGrid">
                @foreach($products as $product)
                    <div class="product-card {{ !$product->isInStock() ? 'out-of-stock' : '' }}" 
                        data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}" 
                        data-price="{{ $product->price }}" 
                        data-stock="{{ $product->stock }}">
                        
                        <div class="product-image-container">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}"
                                    class="product-image">
                            @else
                                <div class="product-image-placeholder">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            @endif
                            
                            {{-- Badge Stok Habis --}}
                            @if(!$product->isInStock())
                                <div class="stock-badge out-of-stock-badge">
                                    <i class="fas fa-times-circle"></i> Stok Habis
                                </div>
                            @elseif($product->stock !== null && $product->stock <= 10 && $product->stock > 0)
                                <div class="stock-badge low-stock-badge">
                                    <i class="fas fa-exclamation-triangle"></i> Stok: {{ $product->stock }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="product-info">
                            <div class="product-name" title="{{ $product->name }}">
                                {{ Str::limit($product->name, 20) }}
                            </div>
                            
                            <div class="product-details">
                                <div class="product-price">{{ $product->formatted_price }}</div>
                                
                                {{-- Info Stok Tambahan --}}
                                @if($product->stock !== null)
                                    <div class="product-stock-info">
                                        @if($product->stock > 10)
                                            <span class="stock-available">
                                                <i class="fas fa-check-circle"></i> Stok: {{ $product->stock }}
                                            </span>
                                        @elseif($product->stock > 0 && $product->stock <= 10)
                                            <span class="stock-low">
                                                <i class="fas fa-exclamation-triangle"></i> Sisa: {{ $product->stock }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <div class="product-stock-info">
                                        <span class="stock-unlimited">
                                            <i class="fas fa-infinity"></i> Stok Tersedia
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i>Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 text-center">
                        <small class="text-muted">Total Pembayaran</small>
                        <h2 class="text-success mb-0" id="modalGrandTotal">Rp 0</h2>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="paymentMethod" id="methodCash" value="cash" checked>
                            <label class="btn btn-outline-success" for="methodCash">
                                <i class="fas fa-money-bill-wave me-2"></i>Cash
                            </label>
                            <input type="radio" class="btn-check" name="paymentMethod" id="methodQris" value="qris">
                            <label class="btn btn-outline-primary" for="methodQris">
                                <i class="fas fa-qrcode me-2"></i>QRIS
                            </label>
                        </div>
                    </div>

                    <div id="cashPaymentSection">
                        <div class="mb-3">
                            <label class="form-label">Jumlah Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control form-control-lg" id="amountPaid" placeholder="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary quick-amount"
                                    data-amount="exact">Uang Pas</button>
                                <button type="button" class="btn btn-outline-secondary quick-amount"
                                    data-amount="10000">10.000</button>
                                <button type="button" class="btn btn-outline-secondary quick-amount"
                                    data-amount="20000">20.000</button>
                                <button type="button" class="btn btn-outline-secondary quick-amount"
                                    data-amount="50000">50.000</button>
                                <button type="button" class="btn btn-outline-secondary quick-amount"
                                    data-amount="100000">100.000</button>
                            </div>
                        </div>

                        <div class="alert alert-info mb-0" id="changeSection" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Kembalian:</span>
                                <span class="fs-4 fw-bold" id="changeAmount">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <div id="qrisPaymentSection" style="display: none;">
                        <div class="text-center py-4">
                            <i class="fas fa-qrcode fa-4x text-primary mb-3"></i>
                            <p class="text-muted mb-0">Pembayaran QRIS akan langsung tercatat sebagai lunas</p>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="orderNotes" rows="2" placeholder="Catatan pesanan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="processPayment">
                        <i class="fas fa-check me-2"></i>Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>Transaksi Berhasil!</h4>
                    <p class="text-muted mb-0" id="successInvoice"></p>
                    <p class="fs-3 fw-bold text-success" id="successTotal"></p>
                    <div id="successChange" style="display: none;">
                        <p class="text-muted mb-0">Kembalian</p>
                        <p class="fs-4 fw-bold text-info" id="successChangeAmount"></p>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="newTransaction">
                        <i class="fas fa-plus me-2"></i>Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" id="errorToast">
            <div class="d-flex">
                <div class="toast-body" id="errorMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // State
            let cart = [];
            let orderType = 'dine_in';
            let grandTotalValue = 0;

            // Elements
            const cartItems = document.getElementById('cartItems');
            const cartEmpty = document.getElementById('cartEmpty');
            const checkoutBtn = document.getElementById('checkoutBtn');
            const searchInput = document.getElementById('searchInput');
            const productsGrid = document.getElementById('productsGrid');

            // Format currency
            function formatRupiah(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID');
            }

            // Add to cart
            function addToCart(product) {
                const existing = cart.find(item => item.id === product.id);
                if (existing) {
                    existing.quantity++;
                } else {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: parseFloat(product.price),
                        quantity: 1
                    });
                }
                updateCart();
            }

            // Update cart display
            function updateCart() {
                if (cart.length === 0) {
                    cartEmpty.style.display = 'flex';
                    checkoutBtn.disabled = true;
                    document.getElementById('subtotal').textContent = 'Rp 0';
                    document.getElementById('grandTotal').textContent = 'Rp 0';
                    return;
                }

                cartEmpty.style.display = 'none';
                checkoutBtn.disabled = false;

                // Build cart HTML
                let html = '';
                cart.forEach((item, index) => {
                    html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">${formatRupiah(item.price)}</div>
                        </div>
                        <div class="cart-item-qty">
                            <button type="button" class="btn btn-light qty-btn" data-action="decrease" data-index="${index}">-</button>
                            <input type="number" value="${item.quantity}" min="1" data-index="${index}" class="qty-input">
                            <button type="button" class="btn btn-light qty-btn" data-action="increase" data-index="${index}">+</button>
                        </div>
                        <div class="cart-item-total">${formatRupiah(item.price * item.quantity)}</div>
                        <span class="cart-item-remove" data-index="${index}"><i class="fas fa-times"></i></span>
                    </div>
                `;
                });

                // Keep empty div but hide it
                cartItems.innerHTML = html + '<div class="cart-empty" id="cartEmpty" style="display:none"><i class="fas fa-shopping-basket"></i><p>Keranjang kosong</p></div>';

                calculateTotals();
            }

            // Calculate totals
            function calculateTotals() {
                const items = cart.map(item => ({
                    price: item.price,
                    quantity: item.quantity
                }));

                const discountType = document.getElementById('discountType').value;
                const discountValue = parseFloat(document.getElementById('discountValue').value) || 0;
                const taxPercent = parseFloat(document.getElementById('taxPercent').value) || 0;
                const servicePercent = parseFloat(document.getElementById('servicePercent').value) || 0;

                fetch('{{ route("pos.calculate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        items,
                        discount_type: discountType,
                        discount_value: discountValue,
                        tax_percent: taxPercent,
                        service_percent: servicePercent
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('subtotal').textContent = formatRupiah(data.subtotal);

                        if (data.discount_amount > 0) {
                            document.getElementById('discountRow').style.display = 'flex';
                            document.getElementById('discountAmount').textContent = '- ' + formatRupiah(data.discount_amount);
                        } else {
                            document.getElementById('discountRow').style.display = 'none';
                        }

                        if (data.tax_amount > 0) {
                            document.getElementById('taxRow').style.display = 'flex';
                            document.getElementById('taxAmount').textContent = formatRupiah(data.tax_amount);
                        } else {
                            document.getElementById('taxRow').style.display = 'none';
                        }

                        if (data.service_amount > 0) {
                            document.getElementById('serviceRow').style.display = 'flex';
                            document.getElementById('serviceAmount').textContent = formatRupiah(data.service_amount);
                        } else {
                            document.getElementById('serviceRow').style.display = 'none';
                        }

                        grandTotalValue = data.grand_total;
                        document.getElementById('grandTotal').textContent = formatRupiah(data.grand_total);
                        document.getElementById('modalGrandTotal').textContent = formatRupiah(data.grand_total);
                    });
            }

            // Product click
            productsGrid.addEventListener('click', function (e) {
                const card = e.target.closest('.product-card');
                if (card && !card.classList.contains('out-of-stock')) {
                    addToCart({
                        id: card.dataset.id,
                        name: card.dataset.name,
                        price: card.dataset.price
                    });
                }
            });

            // Cart interactions
            cartItems.addEventListener('click', function (e) {
                const qtyBtn = e.target.closest('.qty-btn');
                const removeBtn = e.target.closest('.cart-item-remove');

                if (qtyBtn) {
                    const index = parseInt(qtyBtn.dataset.index);
                    const action = qtyBtn.dataset.action;

                    if (action === 'increase') {
                        cart[index].quantity++;
                    } else if (action === 'decrease') {
                        if (cart[index].quantity > 1) {
                            cart[index].quantity--;
                        } else {
                            cart.splice(index, 1);
                        }
                    }
                    updateCart();
                }

                if (removeBtn) {
                    const index = parseInt(removeBtn.dataset.index);
                    cart.splice(index, 1);
                    updateCart();
                }
            });

            // Qty input change
            cartItems.addEventListener('change', function (e) {
                if (e.target.classList.contains('qty-input')) {
                    const index = parseInt(e.target.dataset.index);
                    const newQty = parseInt(e.target.value);
                    if (newQty > 0) {
                        cart[index].quantity = newQty;
                    } else {
                        cart.splice(index, 1);
                    }
                    updateCart();
                }
            });

            // Order type toggle
            document.querySelectorAll('.order-type-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active', 'btn-light'));
                    document.querySelectorAll('.order-type-btn').forEach(b => b.classList.add('btn-outline-light'));
                    this.classList.remove('btn-outline-light');
                    this.classList.add('active', 'btn-light');
                    orderType = this.dataset.type;

                    document.querySelector('.table-input-wrapper').style.display = orderType === 'dine_in' ? 'block' : 'none';
                });
            });

            // Clear cart
            document.getElementById('clearCart').addEventListener('click', function () {
                if (cart.length > 0 && confirm('Hapus semua item dari keranjang?')) {
                    cart = [];
                    updateCart();
                }
            });

            // Category filter
            document.getElementById('categoriesFilter').addEventListener('click', function (e) {
                const btn = e.target.closest('.category-btn');
                if (btn) {
                    document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    filterProducts();
                }
            });

            // Search
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterProducts, 300);
            });

            function filterProducts() {
                const category = document.querySelector('.category-btn.active').dataset.category;
                const search = searchInput.value.toLowerCase();

                document.querySelectorAll('.product-card').forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    const matchSearch = name.includes(search);
                    const matchCategory = !category || card.closest('[data-category="' + category + '"]');

                    // For now, simple filter - in production would use AJAX
                    card.style.display = matchSearch ? '' : 'none';
                });
            }

            // Discount/tax change handlers
            ['discountType', 'discountValue', 'taxPercent', 'servicePercent'].forEach(id => {
                document.getElementById(id).addEventListener('change', calculateTotals);
            });

            // Payment method toggle
            document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    document.getElementById('cashPaymentSection').style.display = this.value === 'cash' ? 'block' : 'none';
                    document.getElementById('qrisPaymentSection').style.display = this.value === 'qris' ? 'block' : 'none';
                });
            });

            // Quick amount buttons
            document.querySelectorAll('.quick-amount').forEach(btn => {
                btn.addEventListener('click', function () {
                    const amount = this.dataset.amount;
                    if (amount === 'exact') {
                        document.getElementById('amountPaid').value = grandTotalValue;
                    } else {
                        document.getElementById('amountPaid').value = parseInt(amount);
                    }
                    calculateChange();
                });
            });

            // Calculate change
            document.getElementById('amountPaid').addEventListener('input', calculateChange);

            function calculateChange() {
                const paid = parseFloat(document.getElementById('amountPaid').value) || 0;
                const change = paid - grandTotalValue;

                if (change >= 0 && paid > 0) {
                    document.getElementById('changeSection').style.display = 'block';
                    document.getElementById('changeAmount').textContent = formatRupiah(change);
                } else {
                    document.getElementById('changeSection').style.display = 'none';
                }
            }

            // Process payment
            document.getElementById('processPayment').addEventListener('click', function () {
                const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
                const amountPaid = parseFloat(document.getElementById('amountPaid').value) || 0;

                if (paymentMethod === 'cash' && amountPaid < grandTotalValue) {
                    showError('Jumlah bayar kurang dari total!');
                    return;
                }

                const data = {
                    items: cart.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity
                    })),
                    type: orderType,
                    table_number: document.getElementById('tableNumber').value || null,
                    discount_type: document.getElementById('discountType').value || null,
                    discount_value: parseFloat(document.getElementById('discountValue').value) || 0,
                    tax_percent: parseFloat(document.getElementById('taxPercent').value) || 0,
                    service_percent: parseFloat(document.getElementById('servicePercent').value) || 0,
                    payment_method: paymentMethod,
                    amount_paid: paymentMethod === 'cash' ? amountPaid : grandTotalValue,
                    notes: document.getElementById('orderNotes').value
                };

                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                fetch('{{ route("pos.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            // Hide checkout modal
                            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();

                            // Show success modal
                            document.getElementById('successInvoice').textContent = result.order.invoice_number;
                            document.getElementById('successTotal').textContent = formatRupiah(result.order.grand_total);

                            if (paymentMethod === 'cash' && result.order.change_amount > 0) {
                                document.getElementById('successChange').style.display = 'block';
                                document.getElementById('successChangeAmount').textContent = formatRupiah(result.order.change_amount);
                            } else {
                                document.getElementById('successChange').style.display = 'none';
                            }

                            new bootstrap.Modal(document.getElementById('successModal')).show();
                        } else {
                            showError(result.message);
                        }
                    })
                    .catch(error => {
                        showError('Terjadi kesalahan. Silakan coba lagi.');
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-check me-2"></i>Proses Pembayaran';
                    });
            });

            // New transaction
            document.getElementById('newTransaction').addEventListener('click', function () {
                cart = [];
                updateCart();
                document.getElementById('tableNumber').value = '';
                document.getElementById('discountType').value = '';
                document.getElementById('discountValue').value = '';
                document.getElementById('taxPercent').value = '0';
                document.getElementById('servicePercent').value = '0';
                document.getElementById('amountPaid').value = '';
                document.getElementById('orderNotes').value = '';
                document.getElementById('changeSection').style.display = 'none';

                bootstrap.Modal.getInstance(document.getElementById('successModal')).hide();
            });

            // Show error
            function showError(message) {
                document.getElementById('errorMessage').textContent = message;
                new bootstrap.Toast(document.getElementById('errorToast')).show();
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                if (e.key === 'F2' && !checkoutBtn.disabled) {
                    e.preventDefault();
                    new bootstrap.Modal(document.getElementById('checkoutModal')).show();
                }
                if (e.key === 'F3') {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        });
    </script>
@endpush