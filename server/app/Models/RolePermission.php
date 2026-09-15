<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = "permission_role";
    protected $fillable = ['id', 'role_id', 'permission_id'];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

}
