<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Application;
use App\Models\Device;
use App\Models\Registry;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $application = Application::getByAppID($request->input('appID'));
        $device = Device::getByUIDOrCreate($request->input('uID'), ['os' => $request->input('os')]);

        $registry = Registry::getByDevAndAppOrCreate($device->id, $application->id, $request->validated());

        return response()->json(['client-token' => $registry->client_token]);
    }
}
