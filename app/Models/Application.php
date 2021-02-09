<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Application extends Model
{
    use HasFactory;

    protected $casts = [
        'google_api_credentials' => 'json',
        'apple_api_credentials' => 'json'
    ];

    public function registries()
    {
        return $this->hasMany(Registry::class);
    }

    public static function getByAppID(string $appID): Application
    {
        return Cache::rememberForever('APPLICATION_BY_APPID_' . $appID, function () use ($appID) {
            return self::where('appID', $appID)->first();
        });
    }
}
