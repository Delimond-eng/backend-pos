<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'code_barre', 'unit_price', 'stock'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
