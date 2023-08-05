<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    protected $table='product_size';
    protected $fillable=['product_id','size_id','sell_price','discount','purchase_price','discount_type','discount_start_date','discount_end_date'];
    // protected $guarded = ['id'];
    protected $appends = ['discount_value'];
    public function getDiscountValueAttribute()
    {
        $discount_value = 0;
        if (!empty($this->discount_start_date) && !empty($this->discount_end_date)) {
            if (date('Y-m-d') >= $this->discount_start_date && date('Y-m-d') <= $this->discount_end_date) {
                if ($this->discount_type == 'percentage') {
                    $discount_value = $this->sell_price * ($this->discount / 100);
                } else if ($this->discount_type == 'fixed') {
                    $discount_value = $this->discount;
                } else {
                    $discount_value = 0;
                }
            }
        } else {
            if ($this->discount_type == 'percentage') {
                $discount_value = $this->sell_price * ($this->discount / 100);
            } else if ($this->discount_type == 'fixed') {
                $discount_value = $this->discount;
            } else {
                $discount_value = 0;
            }
        }

        $offer = Offer::whereJsonContains('product_ids', (string) $this->id)->where('status', 1)->first();
        if (!empty($offer)) {
            if (date('Y-m-d') >= $offer->start_date && date('Y-m-d') <= $offer->end_date) {
                if ($offer->discount_type == 'percentage') {
                    $discount_value = $this->sell_price * ($offer->discount_value / 100);
                } else if ($offer->discount_type == 'fixed') {
                    $discount_value = $offer->discount_value;
                } else {
                    $discount_value = 0;
                }
            } else {
                if ($offer->discount_type == 'percentage') {
                    $discount_value = $this->sell_price * ($offer->discount_value / 100);
                } else if ($offer->discount_type == 'fixed') {
                    $discount_value = $offer->discount_value;
                } else {
                    $discount_value = 0;
                }
            }
        }

        return $discount_value;
    }
}
