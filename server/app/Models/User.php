<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'langue',
        'email_verified_at',
        'role_id',
        'societe_id',
        'date_block'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role associated with the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class, 'societe_id');
    }

    /**
     * Check if the user has a given permission by name.
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        if (!$this->role || !$this->role->rolePermissions) {
            return false;
        }

        foreach ($this->role->rolePermissions as $rolePermission) {
            if ($rolePermission->permission && $rolePermission->permission->name === $permissionName) {
                return true;
            }
        }

        return false;
    }
}
