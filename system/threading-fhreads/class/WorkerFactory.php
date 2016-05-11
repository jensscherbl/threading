<?php
namespace Threading\Fhreads;

use \Threading\QueueStore;
use \Threading\TaskQueue;
use \Threading\Worker;

final class WorkerFactory implements \Threading\WorkerFactory
{
    /**
     * @inheritDoc
     */
    public function create(): Worker
    {
        return new Worker(
            new Thread,
            new TaskQueue(
                new QueueState,
                new QueueStore
            )
        );
    }
}
