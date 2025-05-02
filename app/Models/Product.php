<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['photo','name','category_id', 'code_barre', 'unit_price', 'stock'];

    protected $casts = [
        'created_at'=>'date:d/m/Y'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
