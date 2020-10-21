<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    public function transaction_detail()
    {
        return $this->hasMany('App\Models\transaction_detail');
    }
    public function cart()
    {
        return $this->hasMany('App\Models\cart');
    }
}
