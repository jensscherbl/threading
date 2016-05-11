<?php
namespace Threading;

use \UnderflowException;

final class QueueStore
{
    /**
     * @var array
     */
    private $items;

    /**
     * @internal
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return bool
     */
    public function empty(): bool
    {
        return (count($this->items) === 0);
    }

    /**
     * @param mixed $item
     */
    public function push($item)
    {
        $this->items[] = $item;
    }

    /**
     * @return mixed
     *
     * @throws UnderflowException
     */
    public function shift()
    {
        if (count($this->items) === 0) {

            throw new UnderflowException(
                '(QueueStore) is empty.'
            );
        }

        return array_shift($this->items);
    }
}
