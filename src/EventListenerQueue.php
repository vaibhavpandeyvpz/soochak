<?php

/*
 * This file is part of vaibhavpandeyvpz/soochak package.
 *
 * (c) Vaibhav Pandey <contact@vaibhavpandey.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Soochak;

/**
 * Priority queue for event listeners with FIFO ordering for same priority.
 *
 * This class extends SplPriorityQueue to provide priority-based ordering of
 * event listeners. When multiple listeners have the same priority, they are
 * executed in first-in-first-out (FIFO) order based on insertion time.
 *
 * @extends \SplPriorityQueue
 */
class EventListenerQueue extends \SplPriorityQueue
{
    /**
     * Serial counter for maintaining insertion order.
     *
     * Used to ensure FIFO ordering when multiple items have the same priority.
     * Starts at PHP_INT_MAX and decrements with each insertion.
     *
     * @var int
     */
    protected $serial = PHP_INT_MAX;

    /**
     * Inserts a value into the queue with the given priority.
     *
     * If the priority is an integer, it is converted to an array containing
     * the priority and a serial number to maintain FIFO order for items
     * with the same priority.
     *
     * @param  mixed  $value  The listener callback to insert
     * @param  mixed  $priority  The priority value (integer) or array [priority, serial]
     */
    public function insert(mixed $value, mixed $priority): true
    {
        if (is_int($priority)) {
            $priority = [$priority, $this->serial--];
        }

        parent::insert($value, $priority);

        return true;
    }
}
