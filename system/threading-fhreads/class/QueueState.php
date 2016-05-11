<?php
namespace Threading\Fhreads;

final class QueueState implements \Threading\QueueState
{
    /**
     * @var int
     */
    private $mutex;

    /**
     * @var int
     */
    private $condition;

    /**
     * @internal
     */
    public function __construct()
    {
        $this->mutex     = fhread_mutex_init();
        $this->condition = fhread_cond_init();
    }

    /**
     * @internal
     */
    public function __destruct()
    {
        fhread_cond_destroy(
            $this->condition
        );

        fhread_mutex_destroy(
            $this->mutex
        );
    }

    /**
     * @inheritDoc
     */
    public function lock()
    {
        fhread_mutex_lock(
            $this->mutex
        );
    }

    /**
     * @inheritDoc
     */
    public function unlock()
    {
        fhread_mutex_unlock(
            $this->mutex
        );
    }

    /**
     * @inheritDoc
     */
    public function wait()
    {
        fhread_cond_wait(
            $this->condition,
            $this->mutex
        );
    }

    /**
     * @inheritDoc
     */
    public function signal()
    {
        fhread_cond_signal(
            $this->condition
        );
    }
}
