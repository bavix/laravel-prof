<?php

namespace Bavix\Prof\Jobs;

use Bavix\Prof\Models\Entry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class BulkWriter implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Entry
     */
    protected $entry;

    /**
     * @var array[]
     */
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param Entry $model
     * @param array[] $data
     * @return void
     */
    public function __construct(Entry $model, ?array $data = null)
    {
        if ($data === null) {
            $data = $model->toArray();
        }

        $this->entry = $model;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->data) {
            \array_walk_recursive($this->data, static function (&$value) {
                $value = $value ?? raw('NULL');
            });

            $this->entry::insert($this->data);
        }
    }

}
