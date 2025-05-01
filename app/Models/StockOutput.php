<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOutput extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'reason', 'date', 'user_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
