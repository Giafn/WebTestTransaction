<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_products')->withPivot('quantity');
    }

    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class, 'transaction_payment_methods');
    }
}

