<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGroup extends Model
{
    use HasFactory;

     protected $fillable = ['name', 'description', 'field_id'];

     public function field()
     {
         return $this->belongsTo(Field::class);
     }
}
