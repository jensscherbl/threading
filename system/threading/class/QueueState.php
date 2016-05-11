<?php
namespace Threading;

interface QueueState
{
    /**
     * Locks a mutex.
     */
    public function lock();

    /**
     * Unlocks a mutex.
     */
    public function unlock();

    /**
     * Waits for a condition to be signaled.
     */
    public function wait();

    /**
     * Signals a condition.
     */
    public function signal();
}
