<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_size')->withPivot('purchase_price','sell_price','discount_type','discount','discount_start_date','discount_end_date');
    }
}
