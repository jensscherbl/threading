<?php
namespace Threading;

use \LogicException;
use \RuntimeException;

interface Thread
{
    /**
     * Executes a task concurrently.
     *
     * @param callable $task
     *
     * @throws LogicException
     * @throws RuntimeException
     */
    public function execute(callable $task);

    /**
     * Blocks until the task is finished.
     *
     * @throws LogicException
     * @throws RuntimeException
     */
    public function join();
}
