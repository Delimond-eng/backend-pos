<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'status', 'user_id'];

    protected $casts=[
        "date"=>"date:d/m/y h:i",
        "created_at"=>"date:d/m/y h:i",
        "updated_at"=>"date:d/m/y h:i",
    ];

    public function lines()
    {
        return $this->hasMany(InventoryLine::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
