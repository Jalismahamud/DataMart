<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    protected $table = 'product_sizes';

    protected $fillable = [
        'product_id',
        'size',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
