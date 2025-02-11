<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRecipient extends Model
{
    use HasFactory;
    protected $fillable = [
        'notification_id',
        'recipientable_id',
        'recipientable_type',
        'email',
    ];

    public function recipientable()
    {
        return $this->morphTo();
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
