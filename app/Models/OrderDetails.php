<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $appends = ['variation_pos_model_id'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }

    public function getVariationPosModelIdAttribute()
    {
        return !empty($this->variation) ? $this->variation->pos_model_id : null;
    }
}
