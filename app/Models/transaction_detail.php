<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction_detail extends Model
{
    use HasFactory;
    public function transaction()
    {
        return $this->belongsTo('App\Models\transaction', 'transaction_id', 'id')->withDefault();
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Book', 'book_id', 'id')->withDefault();
    }
}
