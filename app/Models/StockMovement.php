<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'type'];


    protected $casts = [
        "created_at"=>"date:d/m/y h:i"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
