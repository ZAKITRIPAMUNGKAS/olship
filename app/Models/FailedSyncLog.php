<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedSyncLog extends Model
{
    protected $fillable = [
        'type',
        'payload',
        'error_message',
        'attempts',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
