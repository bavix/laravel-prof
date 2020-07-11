<?php

namespace Bavix\Prof\Services;

use Bavix\Prof\Models\ProfileLogEntry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileLogService
{

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string
     */
    protected $clientIp;

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var int|string|null
     */
    protected $userId;

    /**
     * @var float[]
     */
    protected $ticks = [];

    /**
     * ProfileLogService constructor.
     */
    public function __construct()
    {
        $this->requestId = $this->requestId(\config('prof.requestIdLength', 16));
        $this->clientIp = \request()->getClientIp();
        $this->version = \app()->version();
        $this->hostname = \gethostname();
        $this->userId = Auth::id();
    }

    /**
     * @param string $eventName
     * @param string|null $target
     */
    public function tick(string $eventName, ?string $target = null): void
    {
        $currentTime = \microtime(true);
        if (empty($this->ticks[$eventName])) {
            if (!$target) {
                $target = \request()->getRequestUri();
            }

            $this->ticks[$eventName] = [$currentTime, $target];
            return;
        }

        // load data from Tick
        [$tickTime, $target] = $this->ticks[$eventName];

        $entry = new ProfileLogEntry();
        $entry->fill([
            'hostname' => $this->hostname,
            'project' => \env('APP_NAME'),
            'version' => $this->version,
            'userId' => $this->userId ?: raw('NULL'),
            'sessionId' => \session()->getId(),
            'requestId' => $this->requestId,
            'requestIp' => $this->clientIp,
            'eventName' => $eventName,
            'target' => $target,
            'latency' => $currentTime - $tickTime,
            'memoryPeak' => \memory_get_usage(true),
            'date' => $currentTime,
            'created' => $currentTime,
        ]);

        // save via queue if enabled
        $entry->save();

        unset($this->ticks[$eventName]);
    }

    /**
     * @return void
     */
    public function recordAllTicks(): void
    {
        foreach ($this->ticks as $eventName => $tickData) {
            $this->tick($eventName);
        }
    }

    /**
     * Identification of the request (request signature).
     *
     * @param int $length
     * @return string
     */
    public function requestId(int $length = 16): string
    {
        try {
            $reqId = \random_bytes($length >> 1);
        } catch (\Throwable $e) {
            $reqId = Str::random($length >> 1);
        } finally {
            return \bin2hex($reqId);
        }
    }

}
