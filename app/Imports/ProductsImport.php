<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class ProductsImport implements ToModel, WithHeadingRow
{
    use Importable;

    protected string $database;

    public function __construct(string $database)
    {
        $this->database = $database;
    }

    public function model(array $row)
    {
        return new Product([
            'name' => $row['name'] ?? $row['Name'] ?? '',
            'detail' => $row['detail'] ?? $row['Detail'] ?? $row['description'] ?? $row['Description'] ?? null,
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
