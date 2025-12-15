<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WarungPOS') }} - POS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary: #6f42c1;
            --primary-hover: #5a32a3;
            --success: #00d25b;
            --warning: #ffab00;
            --danger: #fc424a;
            --dark: #191c24;
            --body-bg: #f4f5f7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--body-bg);
            height: 100vh;
            overflow: hidden;
        }

        /* POS Layout */
        .pos-container {
            display: flex;
            height: 100vh;
        }

        /* Left Panel - Cart */
        .cart-panel {
            width: 400px;
            background: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .cart-header {
            padding: 1rem 1.25rem;
            background: var(--dark);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .cart-type-toggle {
            display: flex;
            gap: 0.5rem;
        }

        .cart-type-toggle .btn {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 0.5rem;
            background: #f8f9fa;
            margin-bottom: 0.5rem;
        }

        .cart-item:hover {
            background: #f0f0f0;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .cart-item-price {
            font-size: 0.75rem;
            color: #666;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-item-qty input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            padding: 0.25rem;
            font-size: 0.875rem;
        }

        .cart-item-qty button {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.75rem;
        }

        .cart-item-total {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--primary);
            min-width: 80px;
            text-align: right;
        }

        .cart-item-remove {
            margin-left: 0.5rem;
            color: var(--danger);
            cursor: pointer;
            opacity: 0.6;
        }

        .cart-item-remove:hover {
            opacity: 1;
        }

        .cart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #999;
        }

        .cart-empty i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        /* Cart Footer - Summary */
        .cart-footer {
            padding: 1rem;
            border-top: 1px solid #eee;
            background: #fff;
        }

        .cart-summary {
            margin-bottom: 1rem;
        }

        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            font-size: 0.875rem;
        }

        .cart-summary-row.discount {
            color: var(--danger);
        }

        .cart-summary-row.total {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--success);
            border-top: 2px solid #eee;
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }

        .cart-actions {
            display: flex;
            gap: 0.5rem;
        }

        .cart-actions .btn {
            flex: 1;
            padding: 0.75rem;
            font-weight: 600;
        }

        /* Right Panel - Products */
        .products-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .products-header {
            padding: 1rem 1.5rem;
            background: #fff;
            display: flex;
            gap: 1rem;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .products-search {
            flex: 1;
            position: relative;
        }

        .products-search input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .products-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        }

        .products-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .products-categories {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.25rem;
        }

        .products-categories::-webkit-scrollbar {
            height: 4px;
        }

        .category-btn {
            padding: 0.375rem 1rem;
            border: 1px solid #ddd;
            border-radius: 2rem;
            background: #fff;
            cursor: pointer;
            white-space: nowrap;
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .category-btn:hover,
        .category-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .products-grid {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
            align-content: start;
        }

        .product-card {
            background: #fff;
            border-radius: 0.75rem;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card.out-of-stock {
            opacity: 0.5;
            pointer-events: none;
        }

        .product-image {
            height: 120px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-image i {
            font-size: 2rem;
            color: #ccc;
        }

        .product-info {
            padding: 0.75rem;
        }

        .product-name {
            font-weight: 500;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-weight: 700;
            color: var(--success);
            font-size: 0.875rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Modal */
        .modal-header {
            background: var(--dark);
            color: #fff;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .cart-panel {
                width: 320px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .pos-container {
                flex-direction: column-reverse;
            }

            .cart-panel {
                width: 100%;
                height: 45vh;
            }

            .products-panel {
                height: 55vh;
            }
        }

        /* Keyboard hints */
        .keyboard-hint {
            font-size: 0.65rem;
            background: rgba(0, 0, 0, 0.1);
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            margin-left: 0.5rem;
        }
    </style>
</head>

<body>
    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>