<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dialogue extends Model
{
    public function conversation()
{
    return $this->belongsTo(Conversation::class);
}
}
