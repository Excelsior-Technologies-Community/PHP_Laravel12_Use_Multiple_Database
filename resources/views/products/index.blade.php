<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Product Manager</title>

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
            max-width: 1400px;
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

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        .stat h3 {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .stat .number {
            font-size: 30px;
            font-weight: bold;
            margin-top: 8px;
        }

        .toolbar {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        .filters {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto auto;
            gap: 12px;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            background: white;
        }

        .btn {
            border: none;
            padding: 11px 17px;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            display: inline-block;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-warning {
            background: #d97706;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn:hover {
            opacity: .9;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .database-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .database-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        .database-header {
            padding: 18px 20px;
            color: white;
        }

        .primary {
            background: #2563eb;
        }

        .secondary {
            background: #059669;
        }

        .database-header h2 {
            margin: 0 0 5px;
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
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f9fafb;
        }

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .actions .btn {
            padding: 7px 10px;
            font-size: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Numeric Pagination Only
        |--------------------------------------------------------------------------
        */

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 20px;
        }

        .pagination a,
        .pagination span {
            min-width: 36px;
            height: 36px;
            padding: 8px 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            color: #374151;
            background: white;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination a:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .pagination .active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
            font-weight: bold;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #6b7280;
        }

        .section-title {
            padding: 20px 20px 0;
        }

        .action-bar {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media(max-width: 1000px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .database-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media(max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .navbar {
                flex-direction: column;
                gap: 12px;
                padding: 15px 20px;
            }

            .nav-links a {
                margin: 0 6px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">

    <h2>
        Multi-Database Manager
    </h2>

    <div class="nav-links">

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
            Product Management
        </h1>

        <p>
            Manage products stored in both MySQL databases.
        </p>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
         ========================================================= --}}

    @if(session('success'))

        <div class="alert success">

            <strong>Success:</strong>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
         CREATE SUCCESS
         ========================================================= --}}

    @if(session('created'))

        <div class="alert success">

            <strong>Product Created:</strong>

            {{ session('created') }}

        </div>

    @endif


    {{-- =========================================================
         UPDATE SUCCESS
         ========================================================= --}}

    @if(session('updated'))

        <div class="alert success">

            <strong>Product Updated:</strong>

            {{ session('updated') }}

        </div>

    @endif


    {{-- =========================================================
         DELETE SUCCESS
         ========================================================= --}}

    @if(session('deleted'))

        <div class="alert success">

            <strong>Product Deleted:</strong>

            {{ session('deleted') }}

        </div>

    @endif


    {{-- =========================================================
         SYNC SUCCESS
         ========================================================= --}}

    @if(session('synced'))

        <div class="alert success">

            <strong>Synchronization Successful:</strong>

            {{ session('synced') }}

        </div>

    @endif


    {{-- =========================================================
         WARNING MESSAGE
         ========================================================= --}}

    @if(session('warning'))

        <div class="alert warning">

            <strong>Warning:</strong>

            {{ session('warning') }}

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
         ========================================================= --}}

    @if(session('error'))

        <div class="alert error">

            <strong>Error:</strong>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
         ========================================================= --}}

    @if($errors->any())

        <div class="alert error">

            <strong>Please fix the following errors:</strong>

            <ul style="margin-bottom:0;">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         STATISTICS
         ========================================================= --}}

    <div class="stats">

        <div class="stat">

            <h3>
                Primary Products
            </h3>

            <div class="number">
                {{ $primaryTotal }}
            </div>

        </div>


        <div class="stat">

            <h3>
                Secondary Products
            </h3>

            <div class="number">
                {{ $secondaryTotal }}
            </div>

        </div>


        <div class="stat">

            <h3>
                Synchronized
            </h3>

            <div class="number">
                {{ $syncedCount }}
            </div>

        </div>


        <div class="stat">

            <h3>
                Pending Sync
            </h3>

            <div class="number">
                {{ max(0, $primaryTotal - $syncedCount) }}
            </div>

        </div>

    </div>


    {{-- =========================================================
         SEARCH / FILTER / SORT
         ========================================================= --}}

    <div class="toolbar">

        <form
            method="GET"
            action="{{ route('products.index') }}"
        >

            <div class="filters">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search product name or detail..."
                >


                <select name="database">

                    <option
                        value="all"
                        {{ $database === 'all' ? 'selected' : '' }}
                    >
                        All Databases
                    </option>

                    <option
                        value="mysql"
                        {{ $database === 'mysql' ? 'selected' : '' }}
                    >
                        Primary Database
                    </option>

                    <option
                        value="mysql_second"
                        {{ $database === 'mysql_second' ? 'selected' : '' }}
                    >
                        Secondary Database
                    </option>

                </select>


                <select name="sort">

                    <option
                        value="newest"
                        {{ $sort === 'newest' ? 'selected' : '' }}
                    >
                        Newest
                    </option>

                    <option
                        value="oldest"
                        {{ $sort === 'oldest' ? 'selected' : '' }}
                    >
                        Oldest
                    </option>

                    <option
                        value="name_asc"
                        {{ $sort === 'name_asc' ? 'selected' : '' }}
                    >
                        Name A-Z
                    </option>

                    <option
                        value="name_desc"
                        {{ $sort === 'name_desc' ? 'selected' : '' }}
                    >
                        Name Z-A
                    </option>

                </select>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Search
                </button>


                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-secondary"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- =========================================================
         ACTION BUTTONS
         ========================================================= --}}

    <div class="action-bar">

        <a
            href="{{ route('products.create') }}"
            class="btn btn-success"
        >
            + Add Product
        </a>


        <form
            action="{{ route('products.sync-all') }}"
            method="POST"
            style="display:inline;"
            onsubmit="return confirm('Synchronize all primary products to secondary database?');"
        >

            @csrf

            <button
                type="submit"
                class="btn btn-primary"
            >
                Sync All Products
            </button>

        </form>

    </div>


    {{-- =========================================================
         DATABASE GRID
         ========================================================= --}}

    <div class="database-grid">


        {{-- =====================================================
             PRIMARY DATABASE
             ===================================================== --}}

        @if($database === 'all' || $database === 'mysql')

            <div class="database-card">

                <div class="database-header primary">

                    <h2>
                        Primary Database
                    </h2>

                    <small>
                        Connection: mysql
                    </small>

                </div>


                @if($primaryProducts->count() > 0)

                    <div class="table-wrapper">

                        <table>

                            <thead>

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    Detail
                                </th>

                                <th>
                                    Sync
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @foreach($primaryProducts as $product)

                                <tr>

                                    <td>
                                        #{{ $product->id }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $product->name }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ $product->detail ?? 'No detail' }}
                                    </td>


                                    <td>

                                        @if($product->synced)

                                            <span class="badge badge-success">
                                                Synced
                                            </span>

                                        @else

                                            <span class="badge badge-warning">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div class="actions">


                                            {{-- EDIT --}}

                                            <a
                                                href="{{ route(
                                                    'products.edit',
                                                    [
                                                        'database' => 'mysql',
                                                        'id' => $product->id
                                                    ]
                                                ) }}"
                                                class="btn btn-warning"
                                            >
                                                Edit
                                            </a>


                                            {{-- SYNC --}}

                                            @if(!$product->synced)

                                                <form
                                                    action="{{ route(
                                                        'products.sync',
                                                        $product->id
                                                    ) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Synchronize this product to the secondary database?');"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success"
                                                    >
                                                        Sync
                                                    </button>

                                                </form>

                                            @endif


                                            {{-- DELETE --}}

                                            <form
                                                action="{{ route(
                                                    'products.destroy',
                                                    [
                                                        'database' => 'mysql',
                                                        'id' => $product->id
                                                    ]
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         PRIMARY NUMERIC PAGINATION ONLY
                         ================================================= --}}

                    @if($primaryProducts->lastPage() > 1)

                        <div class="pagination">

                            @for(
                                $page = 1;
                                $page <= $primaryProducts->lastPage();
                                $page++
                            )

                                @if($page == $primaryProducts->currentPage())

                                    <span class="active">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a
                                        href="{{ $primaryProducts->url($page) }}"
                                    >
                                        {{ $page }}
                                    </a>

                                @endif

                            @endfor

                        </div>

                    @endif

                @else

                    <div class="empty">

                        No products found in Primary Database.

                    </div>

                @endif

            </div>

        @endif


        {{-- =====================================================
             SECONDARY DATABASE
             ===================================================== --}}

        @if($database === 'all' || $database === 'mysql_second')

            <div class="database-card">

                <div class="database-header secondary">

                    <h2>
                        Secondary Database
                    </h2>

                    <small>
                        Connection: mysql_second
                    </small>

                </div>


                @if($secondaryProducts->count() > 0)

                    <div class="table-wrapper">

                        <table>

                            <thead>

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    Detail
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Actions
                                </th>

                            </tr>

                            </thead>


                            <tbody>

                            @foreach($secondaryProducts as $product)

                                <tr>

                                    <td>
                                        {{ $product->id }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $product->name }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ $product->detail ?? 'No detail' }}
                                    </td>


                                    <td>

                                        @if($product->synced)

                                            <span class="badge badge-success">
                                                Matched
                                            </span>

                                        @else

                                            <span class="badge badge-warning">
                                                Secondary Only
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div class="actions">


                                            {{-- EDIT --}}

                                            <a
                                                href="{{ route(
                                                    'products.edit',
                                                    [
                                                        'database' => 'mysql_second',
                                                        'id' => $product->id
                                                    ]
                                                ) }}"
                                                class="btn btn-warning"
                                            >
                                                Edit
                                            </a>


                                            {{-- SYNC SECONDARY TO PRIMARY --}}

                                            @if(!$product->synced)

                                                <form
                                                    action="{{ route(
                                                        'products.sync-to-primary',
                                                        $product->id
                                                    ) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Synchronize this product to the primary database?');"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success"
                                                    >
                                                        Sync to Primary
                                                    </button>

                                                </form>

                                            @endif


                                            {{-- DELETE --}}

                                            <form
                                                action="{{ route(
                                                    'products.destroy',
                                                    [
                                                        'database' => 'mysql_second',
                                                        'id' => $product->id
                                                    ]
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         SECONDARY NUMERIC PAGINATION ONLY
                         ================================================= --}}

                    @if($secondaryProducts->lastPage() > 1)

                        <div class="pagination">

                            @for(
                                $page = 1;
                                $page <= $secondaryProducts->lastPage();
                                $page++
                            )

                                @if($page == $secondaryProducts->currentPage())

                                    <span class="active">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a
                                        href="{{ $secondaryProducts->url($page) }}"
                                    >
                                        {{ $page }}
                                    </a>

                                @endif

                            @endfor

                        </div>

                    @endif

                @else

                    <div class="empty">

                        No products found in Secondary Database.

                    </div>

                @endif

            </div>

        @endif

    </div>

</div>

</body>

</html>
