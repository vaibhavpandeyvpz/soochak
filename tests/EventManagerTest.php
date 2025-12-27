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
 * Class EventManagerTest
 */
class EventManagerTest extends \PHPUnit\Framework\TestCase
{
    public function test_trigger()
    {
        $em = new EventManager;
        $em->attach('dummy', function (EventInterface $event) {
            echo $event->getName();
        });
        $this->expectOutputString('dummy');
        $em->trigger('dummy');
    }

    public function test_trigger_with_priority()
    {
        $em = new EventManager;
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName().':10';
        }, 10);
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName().':20';
        }, 20);
        $this->expectOutputString('login:20'.'login:10');
        $em->trigger('login');
    }

    public function test_detach()
    {
        $em = new EventManager;
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName().':10';
        });
        $em->attach('login', $detached = function (EventInterface $event) {
            echo $event->getName().':20';
        });
        $em->detach('login', $detached);
        $this->expectOutputString('login:10');
        $em->trigger('login');
    }

    public function test_stop_propagation()
    {
        $em = new EventManager;
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName().':10';
            $event->stopPropagation(true);
        });
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName().':20';
        });
        $this->expectOutputString('login:10');
        $em->trigger($event = new Event('login'));
        $this->assertTrue($event->isPropagationStopped());
    }

    public function test_clear_listeners()
    {
        $em = new EventManager;
        $em->attach('logout', function (EventInterface $event) {
            echo 'listener1';
        });
        $em->attach('logout', function (EventInterface $event) {
            echo 'listener2';
        });
        $em->clearListeners('logout');
        $this->expectOutputString('');
        $em->trigger('logout');
    }

    public function test_trigger_with_params()
    {
        $em = new EventManager;
        $captured = null;
        $em->attach('user.created', function (EventInterface $event) use (&$captured) {
            $captured = $event->getParams();
        });
        $em->trigger('user.created', ['id' => 123, 'name' => 'John']);
        $this->assertEquals(['id' => 123, 'name' => 'John'], $captured);
    }

    public function test_trigger_with_event_object()
    {
        $em = new EventManager;
        $event = new Event('custom.event', ['data' => 'test']);
        $captured = null;
        $em->attach('custom.event', function (EventInterface $e) use (&$captured) {
            $captured = $e->getParams();
        });
        $em->trigger($event, ['additional' => 'param']);
        $this->assertEquals(['additional' => 'param'], $captured);
        $this->assertEquals(['additional' => 'param'], $event->getParams());
    }

    public function test_dispatch_method()
    {
        $em = new EventManager;
        $called = false;
        $em->attach('test.event', function (object $event) use (&$called) {
            $called = true;
        });
        $event = new Event('test.event');
        $result = $em->dispatch($event);
        $this->assertTrue($called);
        $this->assertSame($event, $result);
    }

    public function test_dispatch_with_custom_event_object()
    {
        $em = new EventManager;
        $customEvent = new class
        {
            public string $name = 'custom';
        };
        $called = false;
        $em->attach($customEvent, function (object $event) use (&$called, $customEvent) {
            $this->assertSame($customEvent, $event);
            $called = true;
        });
        $em->dispatch($customEvent);
        $this->assertTrue($called);
    }

    public function test_get_listeners_for_event()
    {
        $em = new EventManager;
        $listener1 = function () {};
        $listener2 = function () {};
        $em->attach('test', $listener1, 10);
        $em->attach('test', $listener2, 20);
        $event = new Event('test');
        $listeners = iterator_to_array($em->getListenersForEvent($event));
        $this->assertCount(2, $listeners);
        $this->assertContains($listener1, $listeners);
        $this->assertContains($listener2, $listeners);
    }

    public function test_multiple_events()
    {
        $em = new EventManager;
        $results = [];
        $em->attach('event1', function () use (&$results) {
            $results[] = 'event1';
        });
        $em->attach('event2', function () use (&$results) {
            $results[] = 'event2';
        });
        $em->trigger('event1');
        $em->trigger('event2');
        $this->assertEquals(['event1', 'event2'], $results);
    }

    public function test_detach_nonexistent_listener()
    {
        $em = new EventManager;
        $em->attach('test', function () {});
        $result = $em->detach('test', function () {});
        $this->assertFalse($result);
    }

    public function test_detach_nonexistent_event()
    {
        $em = new EventManager;
        $result = $em->detach('nonexistent', function () {});
        $this->assertFalse($result);
    }

    public function test_listener_modifies_event()
    {
        $em = new EventManager;
        $em->attach('modify', function (EventInterface $event) {
            $event->setParams(['modified' => true]);
        });
        $event = new Event('modify', ['original' => true]);
        $em->trigger($event);
        $this->assertTrue($event->hasParam('modified'));
        $this->assertTrue($event->getParam('modified'));
    }

    public function test_stop_propagation_with_false()
    {
        $em = new EventManager;
        $results = [];
        $em->attach('test', function (EventInterface $event) use (&$results) {
            $results[] = 'first';
            $event->stopPropagation(true);
        });
        $em->attach('test', function (EventInterface $event) use (&$results) {
            $results[] = 'second';
        });
        $event = new Event('test');
        $em->trigger($event);
        $this->assertEquals(['first'], $results);
        $event->stopPropagation(false);
        $this->assertFalse($event->isPropagationStopped());
    }

    public function test_non_stoppable_event()
    {
        $em = new EventManager;
        $nonStoppableEvent = new class
        {
            public string $name = 'test';
        };
        $results = [];
        $em->attach($nonStoppableEvent, function () use (&$results) {
            $results[] = 'first';
        });
        $em->attach($nonStoppableEvent, function () use (&$results) {
            $results[] = 'second';
        });
        $em->dispatch($nonStoppableEvent);
        $this->assertEquals(['first', 'second'], $results);
    }
}
