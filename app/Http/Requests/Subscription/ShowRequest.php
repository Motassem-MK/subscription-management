<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest {
    public function rules()
    {
        return [
            'client-token' => ['required', 'string', 'exists:registries,client_token'],
        ];
    }
}
