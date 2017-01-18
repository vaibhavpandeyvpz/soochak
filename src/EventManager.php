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
 * Class EventManager
 * @package Soochak
 */
class EventManager implements EventManagerInterface
{
    /**
     * @var EventListenerQueue[]
     */
    protected $listeners = array();

    /**
     * {@inheritdoc}
     */
    public function attach($event, $callback, $priority = 0)
    {
        if (false === array_key_exists($event, $this->listeners)) {
            $this->clearListeners($event instanceof EventInterface ? $event->getName() : $event);
        }
        $this->listeners[$event]->insert($callback, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function clearListeners($event)
    {
        $this->listeners[$event] = new EventListenerQueue();
    }

    /**
     * {@inheritdoc}
     */
    public function detach($event, $callback)
    {
        $found = false;
        if (array_key_exists($event, $this->listeners)) {
            $new = new EventListenerQueue();
            $old = $this->listeners[$event];
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
            $this->listeners[$event] = $new;
        }
        return $found;
    }

    /**
     * {@inheritdoc}
     */
    public function trigger($event, $params = array())
    {
        $result = null;
        if ($event instanceof EventInterface) {
            $name = $event->getName();
            $event->setParams($params);
        } else {
            $name = $event;
            $event = new Event($event, $params);
        }
        if (array_key_exists($name, $this->listeners)) {
            $queue = clone $this->listeners[$event->getName()];
            $queue->top();
            while ($queue->valid()) {
                $callback = $queue->current();
                $queue->next();
                $result = call_user_func($callback, $event);
                if ($event->isPropagationStopped()) {
                    break;
                }
            }
        }
        return $result;
    }
}
