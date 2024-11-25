<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'debitur_id', //foreign key dari table debitur
        'customer_name',
        'slug',
        'work',
        'address',
    ];

    public function setCustomerNameAttribute($value)
    {
        $this->attributes['customer_name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function debitur():BelongsTo
    {
        return $this->belongsTo(Debitur::class);
    }

    public function debts():HasMany
    {
        return $this->hasMany(Debt::class);
    }

    public function photos():HasOne
    {
        return $this->hasOne(CustomerPhoto::class);
    }
}
