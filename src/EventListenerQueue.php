<?php

/*
 * This file is part of vaibhavpandeyvpz/soochak package.
 *
 * (c) Vaibhav Pandey <contact@vaibhavpandey.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 */

namespace Soochak;

/**
 * Class EventListenerQueue
 * @package Soochak
 */
class EventListenerQueue extends \SplPriorityQueue
{
    /**
     * @var int
     */
    protected $serial = PHP_INT_MAX;

    /**
     * @param mixed $value
     * @param int $priority
     */
    public function insert($value, $priority)
    {
        if (is_int($priority)) {
            $priority = array($priority, $this->serial--);
        }
        parent::insert($value, $priority);
    }
}
