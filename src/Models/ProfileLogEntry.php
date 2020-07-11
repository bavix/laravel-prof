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

}
