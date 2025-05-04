<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'date', 'total_amount', 'user_id'];


    protected $casts = [
        "date"=>"date:d/m/y h:i"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

}
