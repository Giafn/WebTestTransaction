<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price'];
    
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_products')->withPivot('quantity');
    }
}

