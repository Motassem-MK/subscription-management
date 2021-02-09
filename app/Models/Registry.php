<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscription()
    {
        return $this->subscriptions()->latest()->first();
    }

    private static function generateToken()
    {
        return md5(uniqid(null, true));
    }

    public static function getByDevAndAppOrCreate($device_id, $application_id, array $attributes): Registry
    {
        return Cache::rememberForever('REGISTRY_DEV_' . $device_id . '_APP_' . $application_id,
            function () use ($device_id, $application_id, $attributes) {
                return Registry::firstOrCreate(
                    ['device_id' => $device_id, 'application_id' => $application_id],
                    $attributes
                );
            });
    }

    public static function getByClientToken(string $client_token) {
        return Cache::rememberForever('REGISTRY_TOKEN_' . $client_token, function() use ($client_token) {
            return Registry::where('client_token', $client_token)->first();
        });
    }

    public function addStartedSubscription(array $attributes) {
        $subscription = new Subscription([
            'status' => Subscription::STATUS_STARTED,
            'receipt' => $attributes['receipt'],
            'expiration_date' => $attributes['expiration_date'],
        ]);

        $this->subscriptions()->save($subscription);
    }
}
