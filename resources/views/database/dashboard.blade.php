<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Multi Database Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #222;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
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

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .page-header p {
            margin: 0;
            color: #6b7280;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .stat-card .title {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
        }

        .primary {
            border-left: 5px solid #2563eb;
        }

        .secondary {
            border-left: 5px solid #16a34a;
        }

        .total {
            border-left: 5px solid #7c3aed;
        }

        .blog {
            border-left: 5px solid #ea580c;
        }

        .section {
            background: white;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .section-header h2 {
            margin: 0;
            font-size: 21px;
        }

        .btn {
            display: inline-block;
            border: none;
            text-decoration: none;
            cursor: pointer;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .database-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .database-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
        }

        .database-card h3 {
            margin-top: 0;
            margin-bottom: 18px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 11px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6b7280;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f9fafb;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        .empty {
            text-align: center;
            padding: 25px;
            color: #6b7280;
        }

        .sync-section {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .sync-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .sync-content p {
            margin: 5px 0 0;
            color: #4b5563;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .action-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 18px;
        }

        .action-card h3 {
            margin: 0 0 8px;
            font-size: 17px;
        }

        .action-card p {
            color: #6b7280;
            font-size: 14px;
            min-height: 40px;
        }

        .footer {
            text-align: center;
            color: #6b7280;
            padding: 20px;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .database-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .container {
                padding: 0 12px;
                margin-top: 20px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .section {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h2>🗄️ Multi Database Manager</h2>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('products.index') }}">Products</a>
            <a href="{{ route('database.health') }}">Database Health</a>
        </div>
    </nav>

    <div class="container">

        <div class="page-header">
            <h1>Database Dashboard</h1>
            <p>Manage and monitor products across your Primary and Secondary databases.</p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
        @endif


        {{-- Statistics --}}
        <div class="stats">

            <div class="stat-card primary">
                <div class="title">Primary Database Products</div>
                <div class="number">
                    {{ $primaryCount ?? 0 }}
                </div>
            </div>

            <div class="stat-card secondary">
                <div class="title">Secondary Database Products</div>
                <div class="number">
                    {{ $secondaryCount ?? 0 }}
                </div>
            </div>

            <div class="stat-card total">
                <div class="title">Total Products</div>
                <div class="number">
                    {{ ($primaryCount ?? 0) + ($secondaryCount ?? 0) }}
                </div>
            </div>

            <div class="stat-card blog">
                <div class="title">Blog Records</div>
                <div class="number">
                    {{ $blogCount ?? 0 }}
                </div>
            </div>

        </div>


        {{-- Quick Actions --}}
        <div class="section">

            <div class="section-header">
                <h2>⚡ Quick Actions</h2>
            </div>

            <div class="quick-actions">

                <div class="action-card">
                    <h3>📦 Manage Products</h3>

                    <p>
                        Search, filter, sort, create, edit and delete products.
                    </p>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-primary">
                        Manage Products
                    </a>
                </div>


                <div class="action-card">
                    <h3>🔄 Synchronization</h3>

                    <p>
                        Synchronize products between Primary and Secondary databases.
                    </p>

                    <form
                        action="{{ route('products.sync-all') }}"
                        method="POST"
                        onsubmit="return confirm('Sync all Primary products to Secondary database?')">
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success">
                            Sync All
                        </button>
                    </form>
                </div>


                <div class="action-card">
                    <h3>❤️ Database Health</h3>

                    <p>
                        Check database connection status, response time and tables.
                    </p>

                    <a
                        href="{{ route('database.health') }}"
                        class="btn btn-secondary">
                        Check Health
                    </a>
                </div>

            </div>

        </div>


        {{-- Database Information --}}
        <div class="section">

            <div class="section-header">
                <h2>🔌 Database Connections</h2>
            </div>

            <div class="database-grid">

                {{-- Primary Database --}}
                <div class="database-card">

                    <h3>🔵 Primary Database</h3>

                    <div class="info-row">
                        <span class="info-label">Connection</span>

                        <span class="info-value">
                            mysql
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Database</span>

                        <span class="info-value">
                            {{ config('database.connections.mysql.database') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Host</span>

                        <span class="info-value">
                            {{ config('database.connections.mysql.host') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Port</span>

                        <span class="info-value">
                            {{ config('database.connections.mysql.port') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Products</span>

                        <span class="info-value">
                            {{ $primaryCount ?? 0 }}
                        </span>
                    </div>

                </div>


                {{-- Secondary Database --}}
                <div class="database-card">

                    <h3>🟢 Secondary Database</h3>

                    <div class="info-row">
                        <span class="info-label">Connection</span>

                        <span class="info-value">
                            mysql_second
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Database</span>

                        <span class="info-value">
                            {{ config('database.connections.mysql_second.database') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Host</span>

                        <span class="info-value">
                            {{ config('database.connections.mysql_second.host') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Port</span>

                        <span class="info-value">
                            {{ config('database.connections.mysql_second.port') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Products</span>

                        <span class="info-value">
                            {{ $secondaryCount ?? 0 }}
                        </span>
                    </div>

                </div>

            </div>

        </div>


        {{-- Synchronization --}}
        <div class="section sync-section">

            <div class="section-header">
                <h2>🔄 Database Synchronization</h2>
            </div>

            <div class="sync-content">

                <div>
                    <strong>Primary → Secondary</strong>

                    <p>
                        Copy all products from the Primary database to the Secondary database.
                    </p>
                </div>

                <form
                    action="{{ route('products.sync-all') }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to synchronize all products?')">
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success">
                        🔄 Sync All Products
                    </button>
                </form>

            </div>

        </div>


        {{-- Latest Primary Products --}}
        <div class="section">

            <div class="section-header">

                <h2>🔵 Latest Primary Products</h2>

                <a
                    href="{{ route('products.index', ['database' => 'primary']) }}"
                    class="btn btn-primary">
                    View All
                </a>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Detail</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($latestPrimary ?? [] as $product)

                        <tr>

                            <td>
                                {{ $product->id }}
                            </td>

                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                {{ $product->detail ?? '-' }}
                            </td>

                            <td>
                                {{ $product->created_at ?? '-' }}
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td
                                colspan="4"
                                class="empty">
                                No products found in Primary database.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Latest Secondary Products --}}
        <div class="section">

            <div class="section-header">

                <h2>🟢 Latest Secondary Products</h2>

                <a
                    href="{{ route('products.index', ['database' => 'secondary']) }}"
                    class="btn btn-success">
                    View All
                </a>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Detail</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($latestSecondary ?? [] as $product)

                        <tr>

                            <td>
                                {{ $product->id }}
                            </td>

                            <td>
                                {{ $product->name }}
                            </td>

                            <td>
                                {{ $product->detail ?? '-' }}
                            </td>

                            <td>
                                {{ $product->created_at ?? '-' }}
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td
                                colspan="4"
                                class="empty">
                                No products found in Secondary database.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <div class="footer">
        Laravel 12 · Multiple MySQL Database Product Manager
    </div>

</body>

</html>