<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $appends = ['discount_value'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'multiple_sizes' => 'array',
        'translations' => 'array',

    ];

    public function getNameAttribute($name)
    {
        $translations = !empty($this->translations['name']) ? $this->translations['name'] : [];
        if (!empty($translations)) {
            $lang = LaravelLocalization::getCurrentLocale();
            if (!empty($translations[$lang])) {
                return $translations[$lang];
            }
        }
        return $name;
    }

    public function category()
    {
        return $this->belongsTo(ProductClass::class, 'product_class_id');
    }


    public function variations()
    {
        return $this->hasMany(Variation::class)->with(['size']);
    }

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
