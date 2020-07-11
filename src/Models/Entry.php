<?php

namespace Bavix\Prof\Models;

use Bavix\LaravelClickHouse\Database\Eloquent\Model;
use Bavix\Prof\Services\BulkService;

abstract class Entry extends Model
{

    /**
     * @inheritDoc
     */
    public function save(array $options = []): bool
    {
        if (\config('prof.saveViaQueue', false)) {
            return \app(BulkService::class)->insert($this);
        }

        return parent::save($options);
    }

}
