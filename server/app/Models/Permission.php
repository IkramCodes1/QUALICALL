<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = "permissions";
    protected $fillable = ['id', 'name'];

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'permission_id');
    }


}
