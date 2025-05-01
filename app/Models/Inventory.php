<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'status', 'user_id'];

    public function lines()
    {
        return $this->hasMany(InventoryLine::class);
    }
}
