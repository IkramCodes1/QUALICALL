<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeTopic extends Model
{
    use SoftDeletes;

    protected $table = 'resume_topic';

    protected $fillable = [
        'conversation_id',
        'topic_id',
        'text',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}