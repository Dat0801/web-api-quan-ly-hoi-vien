<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{
    use HasFactory;
    protected $table = 'membership_fees';

    protected $fillable = [
        'customer_id',
        'customer_type',
        'year',
        'amount_due',
        'amount_paid',
        'remaining_amount',
        'status',
        'content',
        'attachment',
        'payment_date',
        'is_early_payment',
        'payment_years',
    ];

    /**
     * Relationship to get the related customer model.
     */
    public function customer()
    {
        return $this->morphTo('customer', 'customer_type', 'customer_id');
    }
}
