<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display products from both databases.
     */
    public function index()
    {
        try {
            $defaultProducts = DB::connection('mysql')
                ->table('products')
                ->orderBy('id', 'desc')
                ->get();

            $secondProducts = DB::connection('mysql_second')
                ->table('products')
                ->orderBy('id', 'desc')
                ->get();

            return view(
                'products.index',
                compact(
                    'defaultProducts',
                    'secondProducts'
                )
            );
        } catch (Throwable $e) {
            return back()->with(
                'error',
                'Unable to load products: ' . $e->getMessage()
            );
        }
    }

    /**
     * Original dynamic database connection example.
     */
    public function getRecord()
    {
        $product = new \App\Models\Product;

        $product->setConnection('mysql_second');

        return $product->find(1);
    }
}