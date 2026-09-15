<?php

namespace App\Repositories\Conversation;

use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class ConversationRepository
{
    public function store($request)
    {
        return DB::transaction(function () use ($request) {

            // 🎧 upload audio
            $path = null;
            $filename = null;

            if ($request->hasFile('audio')) {
                $file = $request->file('audio');

                $filename = time() . '_' . $file->getClientOriginalName();

                $path = $file->storeAs('audios', $filename, 'public');
            }

            // 💾 create conversation
            $conversation = Conversation::create([
                'name' => $request->name ?? $filename ?? 'Audio',
                'path' => $path,
                'duration' => 0, 
                'call_date' => $request->call_date ?? now(),
                'societe_id' => $request->societe_id ?? 1,
                'categorie_id' => $request->categorie_id,
                'note_ai' => $request->note_ai,

                'is_transcripted' => 0,
                'is_evaluate' => 0,
            ]);


            return $conversation;
        });
    }
}