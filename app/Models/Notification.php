<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'format',
        'sent_at',
        'content',
        'user_id',
    ];

    public function recipients()
    {
        return $this->hasMany(NotificationRecipient::class);
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
