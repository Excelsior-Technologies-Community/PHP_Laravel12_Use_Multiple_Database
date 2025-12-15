# PHP_Laravel12_Use_Multiple_Database

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel">
  <img src="https://img.shields.io/badge/Database-MySQL-blue?style=for-the-badge&logo=mysql">
  <img src="https://img.shields.io/badge/Multiple-Databases-success?style=for-the-badge">
</p>

---

##  Overview

This documentation explains how to **configure and use multiple MySQL databases in Laravel 12**
from **installation to final testing**.

You will learn how to:
- Configure two databases in `.env`
- Register multiple connections in `config/database.php`
- Run migrations on different databases
- Fetch data from default & second database
- Dynamically switch database connection in models

---


---

##  Features

-  Connect multiple MySQL databases in a single Laravel 12 application  
-  Easy configuration using `.env` file  
-  Support for **default & secondary database** connections  
-  Dynamic database switching at runtime  
-  Run migrations on specific databases  
-  Fetch data independently from each database  
-  Simple testing using routes  
-  Fully compatible with Laravel 12  


##  Folder Structure

```
app/
├── Http/
│   └── Controllers/
│       └── ProductController.php
├── Models/
│   └── Product.php
config/
└── database.php
routes/
└── web.php
database/
└── migrations/
.env
README.md
```

---

##  STEP 1: Install Laravel

```bash
composer create-project laravel/laravel multi-db-project
cd multi-db-project
```

---

##  STEP 2: Configure .env File (IMPORTANT)

Open `.env` file and configure **TWO DATABASES**.

```env
# DEFAULT DATABASE (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=

# SECOND DATABASE (MySQL)
DB_CONNECTION_SECOND=mysql
DB_HOST_SECOND=127.0.0.1
DB_PORT_SECOND=3306
DB_DATABASE_SECOND=blog2
DB_USERNAME_SECOND=root
DB_PASSWORD_SECOND=
```

Clear cache:

```bash
php artisan optimize:clear
```

---

##  STEP 3: Configure Multiple Databases

Open:

```
config/database.php
```

Add second MySQL connection:

```php
'default' => env('DB_CONNECTION', 'mysql'),

'connections' => [

    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
    ],

    'mysql_second' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST_SECOND'),
        'port' => env('DB_PORT_SECOND'),
        'database' => env('DB_DATABASE_SECOND'),
        'username' => env('DB_USERNAME_SECOND'),
        'password' => env('DB_PASSWORD_SECOND'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
    ],
];
```

Clear config cache again:

```bash
php artisan config:clear
```

---

##  STEP 4: Create Products Table (Migration)

```bash
php artisan make:migration create_products_table
```

Migration file:

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

Run migration for **default database**:

```bash
php artisan migrate
```

---

##  STEP 5: Create Same Table in Second Database

Edit migration OR create another migration:

```php
Schema::connection('mysql_second')->create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

Run again:

```bash
php artisan migrate
```

✔ Now **both databases** have `products` table.

---

##  STEP 6: Product Model

 `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
}
```

---

##  STEP 7: Controller

```bash
php artisan make:controller ProductController
```

 `app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Fetch record from second database
     */
    public function getRecord()
    {
        $product = new Product;

        // Switch database connection dynamically
        $product->setConnection('mysql_second');

        return $product->find(1);
    }
}
```

---

##  STEP 8: Routes to Fetch Data

 `routes/web.php`

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
| Get Products from Default Database
*/
Route::get('/get-mysql-products', function () {
    $products = DB::table('products')->get();
    dd($products);
});

/*
| Get Products from Second Database
*/
Route::get('/get-mysql-second-products', function () {
    $products = DB::connection('mysql_second')
                  ->table('products')
                  ->get();
    dd($products);
});
```

---

##  STEP 9: Insert Test Data

**Default Database (blog)**

```sql
INSERT INTO products (name)
VALUES ('Product From Default DB');
```

**Second Database (blog2)**

```sql
INSERT INTO products (name)
VALUES ('Product From Second DB');
```

---

##  STEP 10: Final Testing (MOST IMPORTANT)

### Test Default DB

```
http://127.0.0.1:8000/get-mysql-products
```
<img width="502" height="187" alt="Screenshot 2025-12-15 153241" src="https://github.com/user-attachments/assets/9f6dea77-90e7-493f-8881-d9657686a40a" />


### Test Second DB

```
http://127.0.0.1:8000/get-mysql-second-products
```
<img width="502" height="187" alt="Screenshot 2025-12-15 153241" src="https://github.com/user-attachments/assets/dd849f54-4426-445e-bb97-3f52eedd95da" />

---

##  FINAL CONFIRMATION

✔ Laravel installed  
✔ Multiple databases configured  
✔ Migrations working  
✔ Routes working  
✔ Data coming from correct DB  

 **MULTIPLE DATABASE SETUP 100% SUCCESSFUL** 

---

