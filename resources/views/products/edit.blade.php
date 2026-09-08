<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

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
            max-width: 700px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
        }

        h1 {
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 14px;
        }

        textarea {
            min-height: 120px;
        }

        .btn {
            border: none;
            padding: 12px 18px;
            border-radius: 7px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            gap: 10px;
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
                Health
            </a>

        </div>

    </nav>

    <div class="container">

        <div class="card">

            <h1>
                Edit Product
            </h1>

            <p>
                Database:
                <strong>
                    {{ $database === 'mysql'
                    ? 'Primary Database'
                    : 'Secondary Database'
                }}
                </strong>
            </p>

            @if($errors->any())

            <div class="error">

                <ul>

                    @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                    @endforeach

                </ul>

            </div>

            @endif

            <form
                action="{{ route(
                'products.update',
                [
                    'database' => $database,
                    'id' => $product->id
                ]
            ) }}"
                method="POST">

                @csrf

                @method('PUT')

                <div class="form-group">

                    <label>
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required>

                </div>

                <div class="form-group">

                    <label>
                        Product Detail
                    </label>

                    <textarea
                        name="detail">{{ old('detail', $product->detail) }}</textarea>

                </div>

                <div class="buttons">

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Update Product
                    </button>

                    <a
                        href="{{ route('products.index') }}"
                        class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</body>

</html>