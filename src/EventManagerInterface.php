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
 * Interface EventManagerInterface
 * @package Soochak
 */
interface EventManagerInterface
{
    /**
     * @param string $event
     * @param callable $handler
     * @param int $priority
     * @return bool
     */
    public function attach($event, $handler, $priority = 0);

    /**
     * @param string $event
     * @return void
     */
    public function clearListeners($event);

    /**
     * @param string $event
     * @param callable $callback
     * @return bool
     */
    public function detach($event, $callback);

    /**
     * @param string|EventInterface $event
     * @param array|object $params
     * @return mixed
     */
    public function trigger($event, $params = array());
}
