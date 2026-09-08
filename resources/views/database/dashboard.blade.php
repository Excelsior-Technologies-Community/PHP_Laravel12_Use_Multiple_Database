<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Multi Database Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #1f2937;
        }

        .navbar {
            background: #111827;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            color: white;
            margin: 0;
        }

        .nav-links a {
            color: #d1d5db;
            text-decoration: none;
            margin-left: 20px;
        }

        .nav-links a:hover {
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .card h3 {
            margin-top: 0;
            font-size: 15px;
            color: #6b7280;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            margin-top: 10px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            margin-top: 10px;
        }

        .connected {
            background: #dcfce7;
            color: #166534;
        }

        .failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .section-header h2 {
            margin: 0;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 7px;
            padding: 10px 16px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 13px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f9fafb;
        }

        .database-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .db-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px;
        }

        .db-box h3 {
            margin-top: 0;
        }

        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .database-info {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 12px;
            }

            .nav-links a {
                margin: 0 8px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">

    <h2>Multi-Database Manager</h2>

    <div class="nav-links">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('database.health') }}">Health Monitor</a>
    </div>

</nav>

<div class="container">

    <div class="header">
        <h1>Multi-Database Dashboard</h1>
        <p>
            Monitor and manage your Laravel multiple database connections.
        </p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Primary Database</h3>

            <div class="number">
                {{ $defaultProducts }}
            </div>

            <small>Products</small>

            <br>

            @if($defaultConnected)
                <span class="status connected">
                    ● Connected
                </span>
            @else
                <span class="status failed">
                    ● Connection Failed
                </span>
            @endif
        </div>

        <div class="card">
            <h3>Secondary Database</h3>

            <div class="number">
                {{ $secondProducts }}
            </div>

            <small>Products</small>

            <br>

            @if($secondConnected)
                <span class="status connected">
                    ● Connected
                </span>
            @else
                <span class="status failed">
                    ● Connection Failed
                </span>
            @endif
        </div>

        <div class="card">
            <h3>Total Products</h3>

            <div class="number">
                {{ $defaultProducts + $secondProducts }}
            </div>

            <small>Across both databases</small>
        </div>

        <div class="card">
            <h3>Blog Records</h3>

            <div class="number">
                {{ $blogCount }}
            </div>

            <small>Primary database</small>
        </div>

    </div>


    <div class="section">

        <div class="section-header">
            <h2>Database Connections</h2>

            <a
                href="{{ route('database.health') }}"
                class="btn btn-primary"
            >
                Check Health
            </a>
        </div>

        <div class="database-info">

            <div class="db-box">

                <h3>Primary Database</h3>

                <p>
                    <strong>Connection:</strong>
                    mysql
                </p>

                <p>
                    <strong>Database:</strong>
                    {{ $defaultDatabase }}
                </p>

                <p>
                    <strong>Status:</strong>

                    @if($defaultConnected)
                        <span class="status connected">
                            Connected
                        </span>
                    @else
                        <span class="status failed">
                            Failed
                        </span>
                    @endif
                </p>

            </div>


            <div class="db-box">

                <h3>Secondary Database</h3>

                <p>
                    <strong>Connection:</strong>
                    mysql_second
                </p>

                <p>
                    <strong>Database:</strong>
                    {{ $secondDatabase }}
                </p>

                <p>
                    <strong>Status:</strong>

                    @if($secondConnected)
                        <span class="status connected">
                            Connected
                        </span>
                    @else
                        <span class="status failed">
                            Failed
                        </span>
                    @endif
                </p>

            </div>

        </div>

    </div>


    <div class="section">

        <div class="section-header">
            <h2>Latest Primary Database Products</h2>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-primary"
            >
                Manage Products
            </a>
        </div>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody>

            @forelse($latestDefaultProducts as $product)

                <tr>
                    <td>{{ $product->id }}</td>

                    <td>{{ $product->name }}</td>

                    <td>
                        {{ $product->detail ?? 'No details' }}
                    </td>

                    <td>
                        {{ $product->created_at }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        No products found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    <div class="section">

        <div class="section-header">
            <h2>Latest Secondary Database Products</h2>
        </div>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Created</th>
                </tr>
            </thead>

            <tbody>

            @forelse($latestSecondProducts as $product)

                <tr>
                    <td>{{ $product->id }}</td>

                    <td>{{ $product->name }}</td>

                    <td>
                        {{ $product->detail ?? 'No details' }}
                    </td>

                    <td>
                        {{ $product->created_at }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4">
                        No products found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>