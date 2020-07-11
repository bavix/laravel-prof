<?php

namespace Bavix\Prof\Models;

use Bavix\LaravelClickHouse\Database\Eloquent\Model;
use Bavix\Prof\Jobs\BulkWriter;

abstract class Entry extends Model
{

    /**
     * @inheritDoc
     */
    public function save(array $options = []): bool
    {
        if (\config('prof.saveViaQueue', false)) {
            $queueName = \config('prof.queueName', 'prof');
            $job = new BulkWriter($this);
            $job->onQueue($queueName);
            \dispatch($job);
            return true;
        }

        return parent::save($options);
    }

}
