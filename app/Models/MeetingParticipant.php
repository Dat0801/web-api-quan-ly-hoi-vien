<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;

    protected $table = 'meeting_attendees';
    protected $fillable = [
        'meeting_id',
        'participantable_id',
        'participantable_type',
        'external_email',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function participantable()
    {
        return $this->morphTo();
    }
}
