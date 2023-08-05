<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault(['name' => '']);
    }

    public function size()
    {
        return $this->belongsTo(Size::class)->withDefault(['name' => '']);
    }
}
