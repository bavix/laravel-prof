<?php

namespace Bavix\Prof\Models;

class ProfileLogEntry extends Entry
{

    /**
     * @var string
     */
    protected $table = 'profile_logs';

    /**
     * @internal
     */
    protected $fillable = [
        'hostname',
        'project',
        'version',
        'userId',
        'sessionId',
        'requestId',
        'requestIp',
        'eventName',
        'target',
        'latency',
        'memoryPeak',
        'date',
        'created',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'created' => 'datetime:Y-m-d H:i:s',
    ];

}
