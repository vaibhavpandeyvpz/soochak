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
 * Concrete Event implementation.
 *
 * This class provides a standard implementation of EventInterface that can be
 * used to represent events in the event system. It supports event names, parameters,
 * targets, and propagation control.
 *
 * @implements EventInterface
 */
class Event implements EventInterface
{
    /**
     * The name of the event.
     *
     * @var string
     */
    protected $name;

    /**
     * Event parameters (key-value pairs).
     *
     * @var array<string, mixed>
     */
    protected $params = [];

    /**
     * Whether event propagation has been stopped.
     *
     * @var bool
     */
    protected $stopped = false;

    /**
     * The target object or context that triggered the event.
     *
     * @var string|object|null
     */
    protected $target;

    /**
     * Constructs a new Event instance.
     *
     * @param  string  $name  The name of the event
     * @param  array|null  $params  Optional event parameters
     */
    public function __construct(string $name, ?array $params = null)
    {
        $this->setName($name);
        $this->setParams($params ?? []);
    }

    /**
     * Gets the event name.
     *
     * @return string The name of the event
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets a specific parameter value by name.
     *
     * Returns the value of the parameter if it exists, or null if it doesn't.
     *
     * @param  string  $name  The parameter name
     * @return mixed The parameter value, or null if not found
     */
    public function getParam(string $name): mixed
    {
        return $this->hasParam($name) ? $this->params[$name] : null;
    }

    /**
     * Gets all event parameters.
     *
     * @return array<string, mixed> All event parameters as key-value pairs
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Gets the event target.
     *
     * The target represents the object or context that triggered the event.
     *
     * @return string|object|null The event target, or null if not set
     */
    public function getTarget(): string|object|null
    {
        return $this->target;
    }

    /**
     * Checks if a parameter exists.
     *
     * @param  string  $key  The parameter key to check
     * @return bool True if the parameter exists, false otherwise
     */
    public function hasParam(string $key): bool
    {
        return array_key_exists($key, $this->params);
    }

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
     * Sets the event name.
     *
     * @param  string  $name  The event name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Sets the event target.
     *
     * The target represents the object or context that triggered the event.
     *
     * @param  string|object|null  $target  The event target
     */
    public function setTarget(string|object|null $target): void
    {
        $this->target = $target;
    }

    /**
     * Sets all event parameters.
     *
     * Replaces all existing parameters with the provided array.
     *
     * @param  array<string, mixed>  $params  The event parameters as key-value pairs
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
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
