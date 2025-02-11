<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    // Tên bảng
    protected $table = 'role_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    public $timestamps = false;

   
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
