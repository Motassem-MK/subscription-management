<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Device extends Model
{
    use HasFactory;

    public const OS_ANDROID = 'ANDROID';
    public const OS_IOS = 'iOS';

    public const SUPPORTED_OPERATING_SYSTEMS = [
        self::OS_ANDROID,
        self::OS_IOS
    ];

    protected $fillable = [
        'uID',
        'os'
    ];

    public function registries()
    {
        return $this->hasMany(Registry::class);
    }

    public static function getByUIDOrCreate(string $uID, array $attributes): Device {
        return Cache::rememberForever('DEVICE_BY_UID_' . $uID, function () use ($uID, $attributes) {
            return Device::firstOrCreate(['uID' => $uID], ['os' => $attributes['os']]);
        });
    }
}
