<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Synchronization</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            width: 92%;
            max-width: 1250px;
            margin: 40px auto;
        }

        .navbar {
            background: #111827;
            padding: 16px 22px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .navbar h2 {
            color: white;
            margin: 0;
            font-size: 22px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            background: #374151;
            padding: 9px 14px;
            border-radius: 6px;
            font-size: 14px;
        }

        .nav-links a:hover {
            background: #4b5563;
        }

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 16px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .info-box strong {
            color: #1e3a8a;
        }

        .action-bar {
            background: white;
            padding: 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .action-bar h3 {
            margin: 0;
            font-size: 18px;
        }

        .sync-all-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 11px 18px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .sync-all-btn:hover {
            background: #1d4ed8;
        }

        .sync-all-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .database-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .database-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .database-header {
            padding: 18px 20px;
            color: white;
        }

        .primary-header {
            background: #2563eb;
        }

        .secondary-header {
            background: #059669;
        }

        .database-header h2 {
            margin: 0 0 5px;
            font-size: 20px;
        }

        .database-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 13px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 13px;
            text-align: left;
            padding: 13px 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 13px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f9fafb;
        }

        .id-badge {
            display: inline-block;
            background: #e5e7eb;
            color: #374151;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .product-name {
            font-weight: bold;
            color: #111827;
        }

        .detail {
            color: #6b7280;
            line-height: 1.4;
        }

        .sync-btn {
            background: #16a34a;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .sync-btn:hover {
            background: #15803d;
        }

        .sync-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .empty-state {
            padding: 35px 20px;
            text-align: center;
            color: #6b7280;
        }

        .empty-state strong {
            display: block;
            color: #374151;
            margin-bottom: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-ready {
            background: #dcfce7;
            color: #166534;
        }

        .footer-note {
            margin-top: 25px;
            background: white;
            padding: 18px;
            border-radius: 10px;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        @media (max-width: 900px) {
            .database-grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .action-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                margin: 20px auto;
            }

            .page-header h1 {
                font-size: 23px;
            }

            .database-header h2 {
                font-size: 18px;
            }

            th,
            td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- Navigation --}}
    <div class="navbar">
        <h2>Multi-Database System</h2>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('products.index') }}">Products</a>
            <a href="{{ route('database.health') }}">Database Health</a>
        </div>
    </div>

    {{-- Page Header --}}
    <div class="page-header">
        <h1>Product Synchronization</h1>
        <p>
            Synchronize products from the primary MySQL database
            to the secondary MySQL database.
        </p>
    </div>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            <strong>Success:</strong>
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <strong>Warning:</strong>
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Error:</strong>
            {{ session('error') }}
        </div>
    @endif

    {{-- Synchronization Rule --}}
    <div class="info-box">
        <strong>Synchronization Rule:</strong>
        Products are matched by their
        <strong>name</strong>, not their database ID.
        This is important because the primary and secondary databases
        can have different IDs for their products.

        <br>

        For example, if the primary database contains
        <strong>Laptop (ID 1)</strong> and the secondary database contains
        <strong>Monitor (ID 1)</strong>, they are treated as different
        products.

        <br>

        When a new product is synchronized, the secondary database
        automatically generates its own ID.
    </div>

    {{-- Action Bar --}}
    <div class="action-bar">

        <div>
            <h3>Synchronization Controls</h3>

            <span class="status-badge status-ready">
                Name-Based Synchronization Enabled
            </span>
        </div>

        @if(isset($defaultProducts) && $defaultProducts->count() > 0)
            <form
                action="{{ route('products.sync-all') }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to synchronize all products?');"
            >
                @csrf

                <button type="submit" class="sync-all-btn">
                    🔄 Sync All Products
                </button>
            </form>
        @endif

    </div>

    {{-- Database Tables --}}
    <div class="database-grid">

        {{-- Primary Database --}}
        <div class="database-card">

            <div class="database-header primary-header">
                <h2>Primary Database</h2>

                <p>
                    Connection:
                    <strong>mysql</strong>
                </p>
            </div>

            @if(isset($defaultProducts) && $defaultProducts->count() > 0)

                <div class="table-wrapper">
                    <table>

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Detail</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($defaultProducts as $product)

                            <tr>

                                <td>
                                    <span class="id-badge">
                                        #{{ $product->id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="product-name">
                                        {{ $product->name }}
                                    </div>
                                </td>

                                <td>
                                    <div class="detail">
                                        {{ $product->detail ?? 'No detail available' }}
                                    </div>
                                </td>

                                <td>

                                    <form
                                        action="{{ route('products.sync', $product->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Synchronize {{ addslashes($product->name) }} to the secondary database?');"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="sync-btn"
                                        >
                                            Sync
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>
                </div>

            @else

                <div class="empty-state">
                    <strong>No Products Found</strong>
                    There are no products available in the primary database.
                </div>

            @endif

        </div>


        {{-- Secondary Database --}}
        <div class="database-card">

            <div class="database-header secondary-header">
                <h2>Secondary Database</h2>

                <p>
                    Connection:
                    <strong>mysql_second</strong>
                </p>
            </div>

            @if(isset($secondProducts) && $secondProducts->count() > 0)

                <div class="table-wrapper">

                    <table>

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Detail</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($secondProducts as $product)

                            <tr>

                                <td>
                                    <span class="id-badge">
                                        #{{ $product->id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="product-name">
                                        {{ $product->name }}
                                    </div>
                                </td>

                                <td>
                                    <div class="detail">
                                        {{ $product->detail ?? 'No detail available' }}
                                    </div>
                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state">
                    <strong>No Products Found</strong>
                    There are no products available in the secondary database.
                </div>

            @endif

        </div>

    </div>


    {{-- Footer Information --}}
    <div class="footer-note">

        <strong>How synchronization works:</strong>

        <br>

        1. Products are read from the primary
        <strong>mysql</strong> database.

        <br>

        2. The system checks the secondary
        <strong>mysql_second</strong> database using the product name.

        <br>

        3. If the product name already exists, the product is skipped.

        <br>

        4. If the product does not exist, it is inserted into the
        secondary database.

        <br>

        5. The secondary database generates its own product ID,
        so matching IDs between databases are not required.

    </div>

</div>

</body>
</html>