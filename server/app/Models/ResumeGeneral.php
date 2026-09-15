<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeGeneral extends Model
{
    use SoftDeletes;

    protected $table = 'resume_general';

    protected $fillable = [
        'conversation_id',
        'text',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
 
}