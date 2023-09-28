<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'is_active'];
    
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_payment_methods');
    }
}

