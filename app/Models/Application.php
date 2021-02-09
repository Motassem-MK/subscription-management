<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
