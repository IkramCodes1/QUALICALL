<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [

        'conversation_id',

        'score_global',

        'politesse_score',

        'clarte_score',

        'respect_script_score',

        'satisfaction_client_score',

        'points_forts',

        'points_faibles',

        'commentaire_ai'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}