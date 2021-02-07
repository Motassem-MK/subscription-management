<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
