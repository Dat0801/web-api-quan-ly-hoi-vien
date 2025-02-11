<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'start_time', 'end_time', 'location', 'content', 'attachment'];

    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
}
