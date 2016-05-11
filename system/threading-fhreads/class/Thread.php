<?php
namespace Threading\Fhreads;

use \LogicException;
use \RuntimeException;

final class Thread implements \Threading\Thread
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $handle;

    /**
     * @var bool
     */
    private $started;

    /**
     * @internal
     */
    public function __construct()
    {
        $this->started = false;
    }

    /**
     * @uses ThreadRunnable
     */
    public function execute(callable $task)
    {
        if ($this->started) {

            throw new LogicException(
                '(Thread) has been started already.'
            );
        }

        if (fhread_create(new ThreadRunnable($task), $this->id, $this->handle) !== 0) {

            throw new RuntimeException(
                '(Thread) couldn\'t be started.'
            );
        }

        $this->started = true;
    }

    /**
     * @inheritDoc
     */
    public function join()
    {
        if (!$this->started) {

            throw new LogicException(
                '(Thread) has not been started yet.'
            );
        }

        if (fhread_join($this->handle, $error) !== 0) {

            throw new RuntimeException(
                '(Thread) couldn\'t be joined.'
            );
        }

        $this->started = false;
    }
}
