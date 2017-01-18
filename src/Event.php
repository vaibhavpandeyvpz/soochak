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
 * Class Event
 * @package Soochak
 */
class Event implements EventInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var bool
     */
    protected $stopped = false;

    /**
     * @var null|string|object
     */
    protected $target;

    /**
     * Event constructor.
     * @param string $name
     * @param array $params
     */
    public function __construct($name, $params = null)
    {
        $this->setName($name);
        $this->setParams((array)$params);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return $this->hasParam($name) ? $this->params[$name] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParam($key)
    {
        return array_key_exists($key, $this->params);
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped()
    {
        return $this->stopped;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation($flag)
    {
        $this->stopped = $flag;
    }
}
