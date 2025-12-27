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
 * Event Manager interface for managing events and listeners.
 *
 * This interface defines the contract for event management, including attaching
 * listeners, triggering events, and managing listener queues. It provides a
 * backward-compatible API for event handling.
 */
interface EventManagerInterface
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
    public function clearListeners(string|object $event): void;

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

    /**
     * Triggers an event with optional parameters.
     *
     * Dispatches an event to all registered listeners. Accepts either a string
     * event name or an EventInterface instance, and optionally merges provided
     * parameters into the event.
     *
     * @param  string|EventInterface  $event  The event name (string) or EventInterface instance
     * @param  array<string, mixed>  $params  Optional parameters to merge into the event
     * @return object The dispatched event object
     */
    public function trigger(string|EventInterface $event, array $params = []): object;
}
