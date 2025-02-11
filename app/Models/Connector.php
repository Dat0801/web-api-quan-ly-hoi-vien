<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connector extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'position',
        'phone',
        'gender',
        'email',
        'club_id'
    ];

    public function clubs()
    {
        return $this->belongsTo(Club::class);
    }

    public function business_customers()
    {
        return $this->belongsTo(BusinessCustomer::class);
    }
}
