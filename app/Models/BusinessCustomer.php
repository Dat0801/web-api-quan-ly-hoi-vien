<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCustomer extends Model
{
    use HasFactory;
    protected $fillable = [
        'login_code',
        'card_code',
        'business_name_vi',
        'business_name_en',
        'business_name_abbr',
        'headquarters_address',
        'branch_address',
        'tax_code',
        'phone',
        'website',
        'fanpage',
        'established_date',
        'charter_capital',
        'pre_membership_revenue',
        'email',
        'business_scale',
        'industry_id',
        'field_id',
        'market_id',
        'target_customer_group_id',
        'certificate_id',
        'awards',
        'commendations',
        'leader_name',
        'leader_position',
        'leader_phone',
        'leader_gender',
        'leader_email',
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

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function targetCustomerGroup()
    {
        return $this->belongsTo(TargetCustomerGroup::class);
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    public function connector()
    {
        return $this->hasMany(Connector::class);
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
