<?php
namespace Threading;

use \LogicException;

final class Worker
{
    /**
     * @var Thread
     */
    private $thread;

    /**
     * @var TaskQueue
     */
    private $tasks;

    /**
     * @var bool
     */
    private $started;

    /**
     * @var bool
     */
    private $loop;

    /**
     * @param Thread    $thread
     * @param TaskQueue $tasks
     */
    public function __construct(Thread $thread, TaskQueue $tasks)
    {
        $this->thread  = $thread;
        $this->tasks   = $tasks;
        $this->started = false;
        $this->loop    = false;
    }

    /**
     * Starts the worker's thread and routine.
     *
     * @throws LogicException
     */
    public function start()
    {
        if ($this->started) {

            throw new LogicException(
                '(Worker) has been started already.'
            );
        }

        // Executes queued tasks concurrently.

        ($this->thread)
            ->execute(function () {

                $this->loop = true;

                do {

                    // Blocks until a tasks is available.

                    $task = ($this->tasks)->shift();

                    // Executes the task.

                    $task();

                } while ($this->loop);
            });

        $this->started = true;
    }

    /**
     * Stops the worker's routine and thread.
     *
     * @throws LogicException
     */
    public function stop()
    {
        if (!$this->started) {

            throw new LogicException(
                '(Worker) has not been started yet.'
            );
        }

        // Pushes a stop task to the queue.

        ($this->tasks)
            ->push(function () {

                $this->loop = false;
            });

        // Blocks until all tasks are finished.

        ($this->thread)
            ->join();

        $this->started = false;
    }

    /**
     * Pushes a task to the queue.
     *
     * @param callable $task
     *
     * @throws LogicException
     */
    public function push(callable $task)
    {
        if (!$this->started) {

            throw new LogicException(
                '(Worker) has not been started yet.'
            );
        }

        ($this->tasks)
            ->push($task);
    }
}
