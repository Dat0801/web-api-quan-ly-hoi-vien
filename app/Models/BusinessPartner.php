<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessPartner extends Model
{
    use HasFactory;

    protected $table = 'business_partners';

    protected $fillable = [
        'login_code',
        'card_code',
        'business_name_vi',
        'business_name_en',
        'business_name_abbr',
        'partner_category',
        'headquarters_address',
        'branch_address',
        'tax_code',
        'phone',
        'website',
        'leader_name',
        'leader_position',
        'leader_phone',
        'leader_gender',
        'leader_email',
        'club_id',
        'status',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function connector()
    {
        return $this->hasMany(Connector::class);
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
