<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory, \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

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
        'product_ids' => 'array',
    ];

    public function products()
    {
        return $this->belongsToJson(Product::class, 'product_ids');
    }
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
