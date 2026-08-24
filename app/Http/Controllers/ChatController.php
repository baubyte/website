<?php

namespace App\Http\Controllers;

use App\Actions\Chat\SendChatMessage;
use App\Http\Requests\ChatMessageRequest;
use Illuminate\Http\JsonResponse;

/**
 * The browser talks only to this endpoint -- it never talks to n8n
 * directly, so the webhook URL/secret never reach the client.
 * Validation lives in `ChatMessageRequest`, the actual proxying/lead
 * recording in `SendChatMessage` -- this controller only turns that result
 * into an HTTP response.
 *
 * Browser --POST /api/chat--> throttle:20,1 -> EnsureSameOrigin ->
 * ChatController -> SendChatMessage --Http (15s)--> n8n webhook.
 */
class ChatController extends Controller
{
    public function __invoke(ChatMessageRequest $request, SendChatMessage $sendChatMessage): JsonResponse
    {
        $result = $sendChatMessage->handle($request);

        if (! $result->successful) {
            return response()->json([
                'error' => 'chat_unavailable',
                'reply' => $result->reply,
            ], 503);
        }

        return response()->json([
            'reply' => $result->reply,
            'conversation_id' => $result->conversationId,
        ]);
    }
}
