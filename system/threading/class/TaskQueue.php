<?php
namespace Threading;

final class TaskQueue
{
    /**
     * @var QueueState
     */
    private $state;

    /**
     * @var QueueStore
     */
    private $store;

    /**
     * @param QueueState $state
     * @param QueueStore $store
     */
    public function __construct(QueueState $state, QueueStore $store)
    {
        $this->state = $state;
        $this->store = $store;
    }

    /**
     * Pushes a task to the store.
     *
     * @param callable $task
     */
    public function push(callable $task)
    {
        ($this->state)
            ->lock();

        ($this->store)
            ->push($task);

        ($this->state)
            ->unlock();

        ($this->state)
            ->signal();
    }

    /**
     * Returns the first available task.
     *
     * @return callable
     */
    public function shift(): callable
    {
        ($this->state)
            ->lock();

        if (($this->store)->empty()) {

            // Blocks until a task is available.

            ($this->state)
                ->wait();
        }

        // Removes the first task from the store.

        $task = ($this->store)->shift();

        ($this->state)
            ->unlock();

        return $task;
    }
}
