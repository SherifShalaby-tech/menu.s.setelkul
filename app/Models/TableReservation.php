<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableReservation extends Model
{
    use HasFactory;
    protected $table="table_reservations";
    protected $fillable=['dining_table_id','status','customer_mobile_number','customer_name','date_and_time','merge_table_id'];
    protected $casts = [
        'merge_table_id' => 'array'
    ];
    public function dining_tables()
    {
        return $this->belongsTo(DiningTable::class, 'dining_table_id');
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
