<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Conversation\ConversationRepository;
use Illuminate\Support\Facades\Http;
use App\Models\Conversation;

class ConversationController extends Controller
{
    protected $repo;

    public function __construct(ConversationRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * 📥 STORE
     */
    public function store(Request $request)
    {
        // ✅ validation (خفيفة باش تخدم مع frontend)
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'audio' => 'required|file|mimes:mp3,wav|max:20480',
            'call_date' => 'nullable|date',
            'societe_id' => 'nullable|exists:societes,id',
            'categorie_id' => 'nullable|exists:categories,id',
            'note_ai' => 'nullable|numeric|min:0|max:100',
            'dialogues' => 'nullable|string'
        ]);

        try {
            $conversation = $this->repo->store($request);

            return response()->json([
                'success' => true,
                'data' => $conversation
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generate($id)
{
    try {

        $conversation = Conversation::findOrFail($id);

        $fullPath = storage_path('app/public/' . $conversation->path);

        // DEBUG
        if (!file_exists($fullPath)) {

            return response()->json([
                'error' => 'Audio file not found',
                'path' => $fullPath
            ], 404);
        }

        $response = Http::post(
            'http://127.0.0.1:8080/process-audio',
            [
                'conversation_id' => $conversation->id,
                'path' => $fullPath,
            ]
        );

        return response()->json([
            'success' => true,
            'fastapi_response' => $response->json()
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
    /**
     * 📄 GET ALL
     */
    public function index()
    {
        $conversations = Conversation::latest()->get();

        return response()->json([
            'data' => $conversations
        ]);
    }

    /**
     * 🔍 SHOW ONE + dialogues
     */
    public function show($id)
{
    $conversation = Conversation::with([
        'dialogues',
        'resumeGeneral',
        'topics',
        'resumeTopics',
        'evaluation'
    ])->find($id);

    if (!$conversation) {

        return response()->json([
            'message' => 'Conversation not found'
        ], 404);
    }

    return response()->json([
        'data' => $conversation
    ]);
}


    /**
     * ❌ DELETE
     */
    public function destroy($id)
    {
        $conversation = Conversation::find($id);

        if (!$conversation) {
            return response()->json([
                'message' => 'Conversation not found'
            ], 404);
        }

        $conversation->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}