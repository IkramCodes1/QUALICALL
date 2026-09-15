<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\ResumeTopic;
use App\Models\ResumeGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConversationAnalysisController extends Controller
{
    public function store(Request $request)
    {
        $conversationId = $request->conversation_id;

        // SAVE GENERAL SUMMARY
        ResumeGeneral::create([
            'conversation_id' => $conversationId,
            'text' => $request->general_summary,
        ]);

        // SAVE TOPICS
        foreach ($request->topics as $topic) {

            $savedTopic = Topic::create([
                'conversation_id' => $conversationId,
                'name' => $topic['topic'],
                'start_time' => $topic['start_time'],
                'end_time' => $topic['end_time'],
            ]);

            // SAVE TOPIC SUMMARY
            ResumeTopic::create([
                'conversation_id' => $conversationId,
                'topic_id' => $savedTopic->id,
                'text' => $topic['summary'],
            ]);
        }

        return response()->json([
            'message' => 'Analysis saved successfully'
        ]);
    }
}