<?php

namespace App\Http\Requests;

use App\Models\Application;
use App\Models\Device;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest {
    public function rules()
    {
        return [
            'uID' => ['required', 'string'],
            'appID' => ['bail', 'required', 'string', 'exists:applications,appID'],
            'language' => ['required', 'string', Rule::in(config('app.supported_languages'))],
            'os' => ['required', 'string', Rule::in(Device::SUPPORTED_OPERATING_SYSTEMS)]
        ];
    }
}
