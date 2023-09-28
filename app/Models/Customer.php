<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['costumer_name'];
    
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

