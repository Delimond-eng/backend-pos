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


    // Relation avec les mouvements de stock
    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Accesseur pour la quantité globale (stock réel)
    public function getStockGlobalAttribute()
    {
        return $this->movements()->sum('quantity'); // Entrées (+) - Sorties (-)
    }
}
