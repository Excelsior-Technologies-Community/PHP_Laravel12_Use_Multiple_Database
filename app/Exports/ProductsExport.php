<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected string $database;

    public function __construct(string $database)
    {
        $this->database = $database;
    }

    public function query()
    {
        if ($this->database === 'all') {
            $primary = DB::connection('mysql')->table('products')->select('id', 'name', 'detail', 'created_at', 'updated_at');
            $secondary = DB::connection('mysql_second')->table('products')->select('id', 'name', 'detail', 'created_at', 'updated_at');

            return $primary->union($secondary);
        }

        return DB::connection($this->database)->table('products')->select('id', 'name', 'detail', 'created_at', 'updated_at');
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Detail', 'Created At', 'Updated At'];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->detail,
            $product->created_at,
            $product->updated_at,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
