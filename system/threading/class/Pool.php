<?php
namespace Threading;

use \LogicException;

final class Pool
{
    /**
     * @var Worker
     */
    private $foreman;

    /**
     * @var WorkerGroup
     */
    private $workers;

    /**
     * @var bool
     */
    private $started;

    /**
     * @param Worker      $foreman
     * @param WorkerGroup $workers
     */
    public function __construct(Worker $foreman, WorkerGroup $workers)
    {
        $this->foreman = $foreman;
        $this->workers = $workers;
        $this->started = false;
    }

    /**
     * Starts workers and foreman.
     *
     * @throws LogicException
     */
    public function start()
    {
        if ($this->started) {

            throw new LogicException(
                '(Pool) has been started already.'
            );
        }

        ($this->workers)
            ->start();

        ($this->foreman)
            ->start();

        $this->started = true;
    }

    /**
     * Stops workers and foreman.
     *
     * @throws LogicException
     */
    public function stop()
    {
        if (!$this->started) {

            throw new LogicException(
                '(Pool) has not been started yet.'
            );
        }

        // Pushes a task to stop all
        // workers the foreman's queue.

        ($this->foreman)
            ->push(function () {

                // Foreman propagates stop to workers.
                // Blocks until all workers are stopped.

                ($this->workers)
                    ->stop();
            });

        // Stops the foreman.

        ($this->foreman)
            ->stop();

        $this->started = false;
    }

    /**
     * Pushes a task to the foreman's queue.
     *
     * @param callable $task
     *
     * @throws LogicException
     */
    public function push(callable $task)
    {
        if (!$this->started) {

            throw new LogicException(
                '(Pool) has not been started yet.'
            );
        }

        ($this->foreman)
            ->push(function () use ($task) {

                // Foreman delegates task to workers.

                ($this->workers)
                    ->push($task);
            });
    }
}
