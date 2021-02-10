<?php

namespace App\Models;

use App\Events\SubscriptionCanceled;
use App\Events\SubscriptionRenewed;
use App\Events\SubscriptionStarted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public const STATUS_STARTED = 'STARTED';
    public const STATUS_RENEWED = 'RENEWED';
    public const STATUS_EXPIRED = 'EXPIRED';

    public const ALL_STATUSES = [
        self::STATUS_STARTED,
        self::STATUS_RENEWED,
        self::STATUS_EXPIRED
    ];

    protected $fillable = [
        'receipt',
        'status',
        'expiration_date',
    ];

    protected $casts = [
        'expiration_date' => 'datetime'
    ];

    public function getIsValidAttribute()
    {
        return in_array($this->status, [self::STATUS_STARTED, self::STATUS_RENEWED]);
    }

    protected static function booted()
    {
        static::created(function ($subscription) {
            switch ($subscription->status) {
                case self::STATUS_STARTED:
                    SubscriptionStarted::dispatch($subscription);
                    break;
                case self::STATUS_RENEWED:
                    SubscriptionRenewed::dispatch($subscription);
                    break;
                case self::STATUS_EXPIRED:
                    SubscriptionCanceled::dispatch($subscription);
                    break;
            }
        });
    }

    public function registry()
    {
        return $this->belongsTo(Registry::class);
    }
}
