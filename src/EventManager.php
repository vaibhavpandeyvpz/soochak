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
 * Event Manager implementation that provides event dispatching capabilities.
 *
 * This class implements EventManagerInterface which extends PSR-14 EventDispatcherInterface
 * and ListenerProviderInterface. It manages event listeners and dispatches events to
 * registered listeners, providing both PSR-14 standard methods and backward-compatible
 * legacy methods.
 *
 * @implements EventManagerInterface
 */
class EventManager implements EventManagerInterface
{
    /**
     * Array of event listener queues, indexed by event name.
     *
     * @var array<string, EventListenerQueue>
     */
    protected $listeners = [];

    /**
     * Attaches a listener to an event.
     *
     * Registers a callback function to be called when the specified event is triggered.
     * Listeners with higher priority values are called first.
     *
     * @param  string|object  $event  The event name (string) or event object
     * @param  callable  $callback  The callback function to execute when the event is triggered
     * @param  int  $priority  The priority of the listener (higher values are called first)
     */
    public function attach(string|object $event, callable $callback, int $priority = 0): void
    {
        $eventName = $this->getEventName($event);
        if (! isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new EventListenerQueue;
        }

        $this->listeners[$eventName]->insert($callback, $priority);
    }

    /**
     * Clears all listeners for a specific event.
     *
     * Removes all registered listeners for the given event, effectively resetting
     * the event's listener queue.
     *
     * @param  string|object  $event  The event name (string) or event object
     */
    public function clear(string|object $event): void
    {
        $eventName = $this->getEventName($event);
        $this->listeners[$eventName] = new EventListenerQueue;
    }

    /**
     * Detaches a specific listener from an event.
     *
     * Removes a previously attached callback from the event's listener queue.
     *
     * @param  string|object  $event  The event name (string) or event object
     * @param  callable  $callback  The callback function to remove
     * @return bool True if the listener was found and removed, false otherwise
     */
    public function detach(string|object $event, callable $callback): bool
    {
        $eventName = $this->getEventName($event);
        $found = false;
        if (isset($this->listeners[$eventName])) {
            $old = $this->listeners[$eventName];

            // Check if the queue is empty before calling top()
            if ($old->isEmpty()) {
                return false;
            }

            $new = new EventListenerQueue;
            $old->setExtractFlags(EventListenerQueue::EXTR_BOTH);
            $old->top();
            while ($old->valid()) {
                $item = $old->current();
                $old->next();
                if ($item['data'] === $callback) {
                    $found = true;

                    continue;
                }
                $new->insert($item['data'], $item['priority']);
            }

            $this->listeners[$eventName] = $new;
        }

        return $found;
    }

    /**
     * Dispatches an event to all registered listeners.
     *
     * Implements PSR-14 EventDispatcherInterface. Calls all listeners registered
     * for the event in priority order. If the event implements StoppableEventInterface
     * and propagation is stopped, remaining listeners are not called.
     *
     * @param  object  $event  The event object to dispatch
     * @return object The event object that was dispatched (may be modified by listeners)
     */
    public function dispatch(object $event): object
    {
        $isStoppable = $event instanceof StoppableEventInterface;
        foreach ($this->getListenersForEvent($event) as $listener) {
            $listener($event);
            if ($isStoppable && $event->isPropagationStopped()) {
                break;
            }
        }

        return $event;
    }

    /**
     * Gets all listeners for a specific event.
     *
     * Implements PSR-14 ListenerProviderInterface. Returns a generator that yields
     * all callables registered for the given event, ordered by priority (highest first).
     * If no listeners are registered, returns an empty array.
     *
     * @param  object  $event  The event object to get listeners for
     * @return iterable<callable> A generator yielding callable listeners
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventName = $this->getEventName($event);
        if (! isset($this->listeners[$eventName])) {
            return [];
        }

        $queue = clone $this->listeners[$eventName];
        if ($queue->isEmpty()) {
            return [];
        }

        $queue->top();
        while ($queue->valid()) {
            yield $queue->current();
            $queue->next();
        }
    }

    /**
     * Extracts the event name from an event identifier.
     *
     * Converts various event representations (string, EventInterface, or object)
     * into a canonical string name for internal storage and lookup.
     *
     * @param  string|object  $event  The event identifier (string, EventInterface, or object)
     * @return string The canonical event name
     */
    protected function getEventName(string|object $event): string
    {
        if ($event instanceof EventInterface) {
            return $event->getName();
        }

        if (is_object($event)) {
            return get_class($event);
        }

        return $event;
    }
}
