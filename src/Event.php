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

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Concrete Event implementation.
 *
 * This class provides a minimal implementation of StoppableEventInterface
 * for event propagation control.
 *
 * @implements StoppableEventInterface
 */
class Event implements StoppableEventInterface
{
    /**
     * Whether event propagation has been stopped.
     */
    protected bool $stopped = false;

    /**
     * Checks if event propagation has been stopped.
     *
     * Implements PSR-14 StoppableEventInterface. Returns true if stopPropagation()
     * has been called, indicating that further listeners should not be called.
     *
     * @return bool True if propagation is stopped, false otherwise
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * Stops event propagation.
     *
     * When called, this prevents further event listeners from being executed.
     * This is useful when a listener has handled the event completely and
     * subsequent processing is not needed.
     *
     * @param  bool  $flag  Whether to stop propagation (default: true)
     */
    public function stopPropagation(bool $flag = true): void
    {
        $this->stopped = $flag;
    }
}
