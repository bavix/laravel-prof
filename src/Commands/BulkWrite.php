<?php

namespace Bavix\Prof\Commands;

use Bavix\Prof\Services\BulkService;
use Bavix\Prof\Jobs\BulkWriter;
use Illuminate\Console\Command;

class BulkWrite extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prof:bulk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull out from redis and throws in the queue for recording';

    /**
     * @return void
     * @throws
     */
    public function handle(): void
    {
        $batchSize = \config('prof.batchSize', 10000);
        $keys = app(BulkService::class)->keys();
        foreach ($keys as $key) {
            [$bulkName, $class] = \explode(':', $key, 2);
            $chunkIterator = app(BulkService::class)
                ->chunkIterator($batchSize, $key);

            foreach ($chunkIterator as $bulkData) {
                $queueName = \config('prof.queueName', 'default');
                $job = new BulkWriter(new $class, $bulkData);
                $job->onQueue($queueName);
                \dispatch($job);
            }
        }
    }

}
