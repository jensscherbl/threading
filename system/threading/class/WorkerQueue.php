<?php
namespace Threading;

final class WorkerQueue
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
     * Pushes a worker to the store.
     *
     * @param Worker $worker
     */
    public function push(Worker $worker)
    {
        ($this->state)
            ->lock();

        ($this->store)
            ->push($worker);

        ($this->state)
            ->unlock();

        ($this->state)
            ->signal();
    }

    /**
     * Returns the first available worker.
     *
     * @return Worker
     */
    public function shift(): Worker
    {
        ($this->state)
            ->lock();

        if (($this->store)->empty()) {

            // Blocks until a worker is available.

            ($this->state)
                ->wait();
        }

        // Removes the first worker from the store.

        $worker = ($this->store)->shift();

        ($this->state)
            ->unlock();

        return $worker;
    }
}
