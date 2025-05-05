<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_name', 'date', 'total_amount', 'user_id'];

    protected $casts=[
        "date"=> "date:d/m/y",
        "created_at"=>"date:d/m/y h:i"
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
