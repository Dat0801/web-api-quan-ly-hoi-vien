<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'industry_id'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function subGroups()
    {
        return $this->hasMany(SubGroup::class);
    }

    public function clubs()
    {
        return $this->hasMany(Club::class);
    }
}
