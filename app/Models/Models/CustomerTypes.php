<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTypes extends Model
{
    use HasFactory;

    protected $table = 'customer_types';
    protected $guarded = [];
}
