<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'login_code',
        'card_code',
        'full_name',
        'position',
        'phone',
        'partner_category',
        'unit',
        'industry_id',
        'field_id',
        'club_id',
        'status',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function sponsorships()
    {
        return $this->morphMany(Sponsorship::class, 'sponsorable');
    }

    public function activities()
    {
        return $this->morphMany(ActivityParticipant::class, 'participantable');
    }

    public function meetings()
    {
        return $this->morphMany(MeetingParticipant::class, 'participantable');
    }
}
