<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = ['costumer_id', 'address'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}