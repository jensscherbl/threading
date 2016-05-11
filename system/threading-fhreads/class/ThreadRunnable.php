<?php
namespace Threading\Fhreads;

final class ThreadRunnable
{
    /**
     * @var callable
     */
    private $task;

    /**
     * @param callable $task
     */
    public function __construct(callable $task)
    {
        $this->task = $task;
    }

    /**
     * Executes the task.
     */
    public function run()
    {
        ($this->task)();
    }
}
