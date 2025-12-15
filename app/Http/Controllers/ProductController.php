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
