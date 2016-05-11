<?php
namespace Threading\Fhreads;

use \Threading\Pool;
use \Threading\QueueStore;
use \Threading\TaskQueue;
use \Threading\Worker;
use \Threading\WorkerGroup;
use \Threading\WorkerQueue;

final class PoolFactory implements \Threading\PoolFactory
{
    /**
     * @inheritDoc
     */
    public function create(int $size): Pool
    {
        $workers = [];

        foreach (range(1, $size) as $index) {

            $workers[] = new Worker(
                new Thread,
                new TaskQueue(
                    new QueueState,
                    new QueueStore
                )
            );
        }

        return new Pool(
            new Worker(
                new Thread,
                new TaskQueue(
                    new QueueState,
                    new QueueStore
                )
            ),
            new WorkerGroup(
                new WorkerFactory,
                new WorkerQueue(
                    new QueueState,
                    new QueueStore
                ),
                $size
            )
        );
    }
}
