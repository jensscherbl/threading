<?php
namespace Threading;

use \LogicException;

final class WorkerGroup
{
    /**
     * @var WorkerFactory
     */
    private $factory;

    /**
     * @var WorkerQueue
     */
    private $queue;

    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $started;

    /**
     * @param WorkerFactory $factory
     * @param WorkerQueue   $queue
     * @param int           $size
     */
    public function __construct(WorkerFactory $factory, WorkerQueue $queue, int $size)
    {
        $this->factory = $factory;
        $this->queue   = $queue;
        $this->size    = $size;
        $this->started = false;
    }

    /**
     * Creates, starts and queues new workers.
     *
     * @throws LogicException
     */
    public function start()
    {
        if ($this->started) {

            throw new LogicException(
                '(WorkerGroup) has been started already.'
            );
        }

        foreach (range(1, $this->size) as $index) {

            // Creates and starts a new worker.

            $worker = ($this->factory)->create();
            $worker->start();

            // Pushes the worker to the queue.

            ($this->queue)
                ->push($worker);
        }

        $this->started = true;
    }

    /**
     * Stops and removes all workers.
     *
     * @throws LogicException
     */
    public function stop()
    {
        if (!$this->started) {

            throw new LogicException(
                '(WorkerGroup) has not been started yet.'
            );
        }

        foreach (range(1, $this->size) as $index) {

            // Blocks until a worker is
            // available and stops it.

            $worker = ($this->queue)->shift();
            $worker->stop();
        }

        $this->started = false;
    }

    /**
     * Pushes a task to the next available worker.
     *
     * @throws LogicException
     */
    public function push(callable $task)
    {
        if (!$this->started) {

            throw new LogicException(
                '(WorkerGroup) has not been started yet.'
            );
        }

        // Blocks until a worker is available.

        $worker = ($this->queue)->shift($task);

        // Pushes the task to the worker's queue.

        $worker->push(
            function () use ($worker, $task) {

                // Executes the original task.

                $task();

                // Pushes the worker back into the
                // group's queue when finished.

                ($this->queue)
                    ->push($worker);
            }
        );
    }
}
