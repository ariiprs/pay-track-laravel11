<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'debt_id', //foreign key from debts table
        'installment_number',
        'total_installment',
        'remaining_amount',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function debt(): BelongsTo
    {
        return $this->belongsTo(Debt::class);
    }


}
