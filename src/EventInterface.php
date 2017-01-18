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
 * Interface EventInterface
 * @package Soochak
 */
interface EventInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @return null|string|object
     */
    public function getTarget();

    /**
     * @param string $key
     * @return bool
     */
    public function hasParam($key);

    /**
     * @return bool
     */
    public function isPropagationStopped();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @param null|string|object $target
     */
    public function setTarget($target);

    /**
     * @param bool $flag
     */
    public function stopPropagation($flag);
}
