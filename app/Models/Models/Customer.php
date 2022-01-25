<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $guarded = [];

    public function customertypes()
    {
        return $this->belongsTo('App\Models\Models\CustomerTypes', 'customer_types_id', 'id')->withDefault();
    }
}
