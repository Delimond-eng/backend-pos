<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;


    protected $fillable = ['expense_type_id', 'date', 'amount', 'description', 'user_id'];

    protected $casts = [
        "date"=> "date:d/m/y"
    ];

    public function type()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
