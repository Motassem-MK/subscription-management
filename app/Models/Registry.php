<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registry extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'application_id',
        'language',
        'client_token'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registry) {
            $registry->client_token = self::generateToken();
        });
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    private static function generateToken()
    {
        return md5(uniqid(null, true));
    }
}
