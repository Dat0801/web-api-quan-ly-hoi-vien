<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityParticipant extends Model
{
    use HasFactory;
    protected $fillable = ['activity_id', 'participantable_id', 'participantable_type', 'external_name', 'external_email'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function participantable()
    {
        return $this->morphTo();
    }
}
