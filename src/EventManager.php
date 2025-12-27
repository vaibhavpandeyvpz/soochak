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

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Event Manager implementation that provides event dispatching capabilities.
 *
 * This class implements PSR-14 EventDispatcherInterface and ListenerProviderInterface,
 * while maintaining backward compatibility with the legacy EventManagerInterface.
 * It manages event listeners and dispatches events to registered listeners.
 *
 * @implements EventManagerInterface
 * @implements EventDispatcherInterface
 * @implements ListenerProviderInterface
 */
class EventManager implements EventDispatcherInterface, EventManagerInterface, ListenerProviderInterface
{
    /**
     * The listener provider that manages event listeners.
     *
     * @var ListenerProvider
     */
    protected $listenerProvider;

    /**
     * Constructs a new EventManager instance.
     *
     * Initializes the internal listener provider for managing event listeners.
     */
    public function __construct()
    {
        $this->listenerProvider = new ListenerProvider;
    }

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
        $this->listenerProvider->attach($event, $callback, $priority);
    }

    /**
     * Clears all listeners for a specific event.
     *
     * Removes all registered listeners for the given event, effectively resetting
     * the event's listener queue.
     *
     * @param  string|object  $event  The event name (string) or event object
     */
    public function clearListeners(string|object $event): void
    {
        $this->listenerProvider->clearListeners($event);
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
        return $this->listenerProvider->detach($event, $callback);
    }

    /**
     * Triggers an event with optional parameters.
     *
     * This is a legacy method that maintains backward compatibility. It accepts
     * either a string event name or an EventInterface instance, and optionally
     * merges provided parameters into the event.
     *
     * @param  string|EventInterface  $event  The event name (string) or EventInterface instance
     * @param  array  $params  Optional parameters to merge into the event
     * @return object The dispatched event object
     */
    public function trigger(string|EventInterface $event, array $params = []): object
    {
        if ($event instanceof EventInterface) {
            $event->setParams($params);
        } elseif (! is_object($event)) {
            $event = new Event($event, $params);
        }

        return $this->dispatch($event);
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
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
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
     * Implements PSR-14 ListenerProviderInterface. Returns an iterable of callables
     * that are registered for the given event, ordered by priority.
     *
     * @param  object  $event  The event object to get listeners for
     * @return iterable<callable> An iterable of callable listeners
     */
    public function getListenersForEvent(object $event): iterable
    {
        return $this->listenerProvider->getListenersForEvent($event);
    }
}
