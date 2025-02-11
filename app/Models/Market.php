<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;
    protected $fillable = [
        'market_code',
        'market_name',
        'description',
    ];

    public function clubs()
    {
        return $this->hasMany(Club::class);
    }
}
