<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * `POST /api/chat` (PR11 chat proxy). No per-user auth exists on this
 * public endpoint -- `EnsureSameOrigin` + `throttle:20,1` (see
 * `routes/web.php`) are the actual access controls, so `authorize()` just
 * clears every request that reaches validation.
 */
class ChatMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'uuid'],
            'locale' => ['required', 'in:es,en'],
            'page' => ['nullable', 'string', 'max:255'],
        ];
    }
}
