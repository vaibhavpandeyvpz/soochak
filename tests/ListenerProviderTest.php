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

/**
 * Class ListenerProviderTest
 */
class ListenerProviderTest extends \PHPUnit\Framework\TestCase
{
    public function test_attach_with_string_event()
    {
        $provider = new ListenerProvider;
        $called = false;
        $provider->attach('test.event', function () use (&$called) {
            $called = true;
        });
        $event = new Event('test.event');
        foreach ($provider->getListenersForEvent($event) as $listener) {
            $listener($event);
        }
        $this->assertTrue($called);
    }

    public function test_attach_with_event_object()
    {
        $provider = new ListenerProvider;
        $called = false;
        $customEvent = new class
        {
            public string $name = 'custom';
        };
        $provider->attach($customEvent, function () use (&$called) {
            $called = true;
        });
        foreach ($provider->getListenersForEvent($customEvent) as $listener) {
            $listener($customEvent);
        }
        $this->assertTrue($called);
    }

    public function test_attach_with_priority()
    {
        $provider = new ListenerProvider;
        $results = [];
        $provider->attach('test', function () use (&$results) {
            $results[] = 'low';
        }, 1);
        $provider->attach('test', function () use (&$results) {
            $results[] = 'high';
        }, 10);
        $event = new Event('test');
        foreach ($provider->getListenersForEvent($event) as $listener) {
            $listener($event);
        }
        $this->assertEquals(['high', 'low'], $results);
    }

    public function test_clear_listeners()
    {
        $provider = new ListenerProvider;
        $provider->attach('test', function () {
            $this->fail('Should not be called');
        });
        $provider->clearListeners('test');
        $event = new Event('test');
        $listeners = iterator_to_array($provider->getListenersForEvent($event));
        $this->assertEmpty($listeners);
    }

    public function test_detach_listener()
    {
        $provider = new ListenerProvider;
        $listener1 = function () {
            $this->fail('Should not be called');
        };
        $listener2 = function () {};
        $provider->attach('test', $listener1);
        $provider->attach('test', $listener2);
        $result = $provider->detach('test', $listener1);
        $this->assertTrue($result);
        $event = new Event('test');
        $listeners = iterator_to_array($provider->getListenersForEvent($event));
        $this->assertCount(1, $listeners);
        $this->assertContains($listener2, $listeners);
        $this->assertNotContains($listener1, $listeners);
    }

    public function test_detach_nonexistent_listener()
    {
        $provider = new ListenerProvider;
        $provider->attach('test', function () {});
        $result = $provider->detach('test', function () {});
        $this->assertFalse($result);
    }

    public function test_detach_nonexistent_event()
    {
        $provider = new ListenerProvider;
        $result = $provider->detach('nonexistent', function () {});
        $this->assertFalse($result);
    }

    public function test_get_listeners_for_event_nonexistent()
    {
        $provider = new ListenerProvider;
        $event = new Event('nonexistent');
        $listeners = iterator_to_array($provider->getListenersForEvent($event));
        $this->assertEmpty($listeners);
    }

    public function test_get_listeners_for_event_with_custom_object()
    {
        $provider = new ListenerProvider;
        $customEvent = new class
        {
            public string $name = 'CustomEvent';
        };
        $called = false;
        $provider->attach($customEvent, function () use (&$called) {
            $called = true;
        });
        $listeners = iterator_to_array($provider->getListenersForEvent($customEvent));
        $this->assertCount(1, $listeners);
        $listeners[0]($customEvent);
        $this->assertTrue($called);
    }

    public function test_multiple_listeners_same_priority()
    {
        $provider = new ListenerProvider;
        $results = [];
        $provider->attach('test', function () use (&$results) {
            $results[] = 'first';
        }, 5);
        $provider->attach('test', function () use (&$results) {
            $results[] = 'second';
        }, 5);
        $provider->attach('test', function () use (&$results) {
            $results[] = 'third';
        }, 5);
        $event = new Event('test');
        foreach ($provider->getListenersForEvent($event) as $listener) {
            $listener($event);
        }
        $this->assertEquals(['first', 'second', 'third'], $results);
    }

    public function test_get_event_name_with_event_interface()
    {
        $provider = new ListenerProvider;
        $event = new Event('test.event');
        $provider->attach($event, function () {});
        $listeners = iterator_to_array($provider->getListenersForEvent($event));
        $this->assertCount(1, $listeners);
    }

    public function test_get_event_name_with_string()
    {
        $provider = new ListenerProvider;
        $provider->attach('string.event', function () {});
        $event = new Event('string.event');
        $listeners = iterator_to_array($provider->getListenersForEvent($event));
        $this->assertCount(1, $listeners);
    }

    public function test_get_event_name_with_object()
    {
        $provider = new ListenerProvider;
        $customEvent = new class {};
        $provider->attach($customEvent, function () {});
        $listeners = iterator_to_array($provider->getListenersForEvent($customEvent));
        $this->assertCount(1, $listeners);
    }
}
