<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', //foreign key to customer table
        'photo'
    ];
}
