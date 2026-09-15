<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'name',
        'start_time',
        'end_time',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function resume()
    {
        return $this->hasOne(ResumeTopic::class);
    }
}