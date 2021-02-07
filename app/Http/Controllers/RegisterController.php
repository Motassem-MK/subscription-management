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
        $application = Application::where('appID', $request->input('appID'))->first();
        $device = Device::firstOrCreate(['uID' => $request->input('uID')], ['os' => $request->input('os')]);

        $registry = Registry::firstOrCreate(
            ['device_id' => $device->id, 'application_id' => $application->id],
            $request->validated()
        );

        return response()->json(['client-token' => $registry->client_token]);
    }
}
