<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    use HasFactory;
    protected $table = 'sponsorships';

    protected $fillable = [
        'sponsorable_id',
        'sponsorable_type',
        'sponsorship_date',
        'content',
        'product',
        'unit',
        'unit_price',
        'quantity',
        'total_amount',
        'attachment',
    ];

    public function sponsorable()
    {
        return $this->morphTo();
    }
}
