<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;
    protected $table = 'industries';
    protected $fillable = ['industry_code', 'industry_name', 'description'];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function clubs()
    {
        return $this->hasMany(Club::class);
    }
}
