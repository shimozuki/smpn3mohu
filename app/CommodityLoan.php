<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Commodity;
use App\User;

class CommodityLoan extends Model
{
    protected $fillable = [
        'commodity_id',
        'user_id',
        'loan_date',
        'due_date',
        'return_date',
        'quantity',
        'status',
        'purpose',
        'note'
    ];

    protected $dates = [
        'loan_date',
        'due_date',
        'return_date'
    ];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}