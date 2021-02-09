<?php

namespace App\Models;

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

    public function registry()
    {
        return $this->belongsTo(Registry::class);
    }
}
