<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Database Health Monitor</title>

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

        .navbar a {
            color: #d1d5db;
            text-decoration: none;
            margin-left: 20px;
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

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        .card h2 {
            margin-top: 0;
        }

        .status {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .info {
            display: grid;
            grid-template-columns: 150px 1fr;
            row-gap: 14px;
            margin-top: 20px;
        }

        .label {
            font-weight: bold;
            color: #6b7280;
        }

        .table-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 7px;
            border: none;
            cursor: pointer;
        }

        .error {
            background: #fff1f2;
            color: #9f1239;
            padding: 12px;
            border-radius: 7px;
            margin-top: 20px;
            word-break: break-word;
            font-size: 13px;
        }

        .response {
            font-size: 24px;
            font-weight: bold;
        }

        @media(max-width: 850px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 12px;
            }
        }

    </style>

</head>

<body>

<nav class="navbar">

    <h2>Multi-Database Manager</h2>

    <div>

        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>

        <a href="{{ route('products.index') }}">
            Products
        </a>

        <a href="{{ route('database.health') }}">
            Health Monitor
        </a>

    </div>

</nav>


<div class="container">

    <div class="header">

        <h1>
            Database Connection Health Monitor
        </h1>

        <p>
            Test the health and availability of every configured database.
        </p>

        <a
            href="{{ route('database.health') }}"
            class="btn"
        >
            Run Health Check Again
        </a>

    </div>


    <div class="grid">

        @foreach($results as $result)

            <div class="card">

                <h2>
                    {{ $result['name'] }}
                </h2>

                @if($result['status'])

                    <span class="status success">
                        ● Connected
                    </span>

                    <div class="info">

                        <div class="label">
                            Connection
                        </div>

                        <div>
                            {{ $result['connection'] }}
                        </div>


                        <div class="label">
                            Database
                        </div>

                        <div>
                            {{ $result['database'] }}
                        </div>


                        <div class="label">
                            Host
                        </div>

                        <div>
                            {{ $result['host'] }}
                        </div>


                        <div class="label">
                            Port
                        </div>

                        <div>
                            {{ $result['port'] }}
                        </div>


                        <div class="label">
                            Response
                        </div>

                        <div class="response">
                            {{ $result['response_time'] }} ms
                        </div>


                        <div class="label">
                            Products Table
                        </div>

                        <div>

                            @if($result['products_table'])

                                <span class="status success">
                                    Available
                                </span>

                            @else

                                <span class="status danger">
                                    Missing
                                </span>

                            @endif

                        </div>


                        <div class="label">
                            Product Count
                        </div>

                        <div>
                            {{ $result['product_count'] }}
                        </div>

                    </div>

                @else

                    <span class="status danger">
                        ● Connection Failed
                    </span>

                    <div class="info">

                        <div class="label">
                            Connection
                        </div>

                        <div>
                            {{ $result['connection'] }}
                        </div>


                        <div class="label">
                            Database
                        </div>

                        <div>
                            {{ $result['database'] }}
                        </div>


                        <div class="label">
                            Host
                        </div>

                        <div>
                            {{ $result['host'] }}
                        </div>


                        <div class="label">
                            Port
                        </div>

                        <div>
                            {{ $result['port'] }}
                        </div>

                    </div>


                    <div class="error">

                        <strong>
                            Error:
                        </strong>

                        {{ $result['error'] }}

                    </div>

                @endif

            </div>

        @endforeach

    </div>

</div>

</body>

</html>