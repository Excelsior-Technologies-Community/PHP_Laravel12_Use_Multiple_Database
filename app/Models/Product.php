<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'detail',
    ];

    /**
     * Primary database connection.
     */
    protected $connection = 'mysql';

    /**
     * Change database connection dynamically.
     */
    public function useDatabase(string $connection): static
    {
        $this->setConnection($connection);

        return $this;
    }
}
