<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Societe extends Model
{
    use SoftDeletes;

    protected $table = 'societes';

    protected $fillable = [
        'name',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'societe_id');
    }

    public function agents()
    {
        return $this->hasMany(Agent::class, 'societe_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'societe_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'societe_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'societe_id');
    }
}
