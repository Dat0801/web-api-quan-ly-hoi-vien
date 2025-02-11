<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetCustomerGroup extends Model
{
    use HasFactory;
    protected $fillable = ['group_code', 'group_name', 'description'];
}
