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

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Event interface for representing events in the event system.
 *
 * This interface extends PSR-14 StoppableEventInterface and provides methods
 * for managing event names, parameters, targets, and propagation control.
 *
 * @extends StoppableEventInterface
 */
interface EventInterface extends StoppableEventInterface
{
    /**
     * Gets the event name.
     *
     * @return string The name of the event
     */
    public function getName(): string;

    /**
     * Gets a specific parameter value by name.
     *
     * @param  string  $name  The parameter name
     * @return mixed The parameter value, or null if not found
     */
    public function getParam(string $name): mixed;

    /**
     * Gets all event parameters.
     *
     * @return array<string, mixed> All event parameters as key-value pairs
     */
    public function getParams(): array;

    /**
     * Gets the event target.
     *
     * The target represents the object or context that triggered the event.
     *
     * @return string|object|null The event target, or null if not set
     */
    public function getTarget(): string|object|null;

    /**
     * Checks if a parameter exists.
     *
     * @param  string  $key  The parameter key to check
     * @return bool True if the parameter exists, false otherwise
     */
    public function hasParam(string $key): bool;

    /**
     * Checks if event propagation has been stopped.
     *
     * @return bool True if propagation is stopped, false otherwise
     */
    public function isPropagationStopped(): bool;

    /**
     * Sets the event name.
     *
     * @param  string  $name  The event name
     */
    public function setName(string $name): void;

    /**
     * Sets all event parameters.
     *
     * @param  array<string, mixed>  $params  The event parameters as key-value pairs
     */
    public function setParams(array $params): void;

    /**
     * Sets the event target.
     *
     * @param  string|object|null  $target  The event target
     */
    public function setTarget(string|object|null $target): void;

    /**
     * Stops event propagation.
     *
     * @param  bool  $flag  Whether to stop propagation (default: true)
     */
    public function stopPropagation(bool $flag = true): void;
}
