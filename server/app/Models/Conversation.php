<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResumeGeneral;
use App\Models\Dialogue;
use App\Models\Topic;

class Conversation extends Model
{
    protected $fillable = [
        'name',
        'path',
        'duration',
        'call_date',
        'categorie_id',
        'societe_id',
        'note_ai',
        'is_transcripted',
        'is_evaluate'
    ];

    public function dialogues()
    {
        return $this->hasMany(
            Dialogue::class,
            'conversation_id'
        );
    }
    public function resumeTopics()
    {
        return $this->hasMany(
            ResumeTopic::class,
            'conversation_id'
        );
    }
    // 🧾 resume
    public function resumeGeneral()
    {
        return $this->hasMany(
            ResumeGeneral::class,
            'conversation_id'
        );
    }
    public function topics()
    {
        return $this->hasMany(
            Topic::class,
            'conversation_id'
        );
    }
    public function evaluation()
    {
        return $this->hasOne(
            Evaluation::class,
            'conversation_id'
        );
    }
    
}