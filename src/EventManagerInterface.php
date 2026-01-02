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

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Event Manager interface for managing events and listeners.
 *
 * This interface extends PSR-14 EventDispatcherInterface and ListenerProviderInterface,
 * while providing additional backward-compatible methods for event handling.
 *
 * @extends EventDispatcherInterface
 * @extends ListenerProviderInterface
 */
interface EventManagerInterface extends EventDispatcherInterface, ListenerProviderInterface
{
    /**
     * Attaches a listener to an event.
     *
     * Registers a callback function to be called when the specified event is triggered.
     * Listeners with higher priority values are called first.
     *
     * @param  string|object  $event  The event name (string) or event object
     * @param  callable  $handler  The callback function to execute when the event is triggered
     * @param  int  $priority  The priority of the listener (higher values are called first)
     */
    public function attach(string|object $event, callable $handler, int $priority = 0): void;

    /**
     * Clears all listeners for a specific event.
     *
     * Removes all registered listeners for the given event.
     *
     * @param  string|object  $event  The event name (string) or event object
     */
    public function clear(string|object $event): void;

    /**
     * Detaches a specific listener from an event.
     *
     * Removes a previously attached callback from the event's listener queue.
     *
     * @param  string|object  $event  The event name (string) or event object
     * @param  callable  $callback  The callback function to remove
     * @return bool True if the listener was found and removed, false otherwise
     */
    public function detach(string|object $event, callable $callback): bool;
}
