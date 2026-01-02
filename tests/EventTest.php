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
 * Class EventTest
 */
class EventTest extends \PHPUnit\Framework\TestCase
{
    public function test_is_propagation_stopped_default()
    {
        $event = new Event;
        $this->assertFalse($event->isPropagationStopped());
    }

    public function test_stop_propagation()
    {
        $event = new Event;
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation(true);
        $this->assertTrue($event->isPropagationStopped());
    }

    public function test_stop_propagation_false()
    {
        $event = new Event;
        $event->stopPropagation(true);
        $this->assertTrue($event->isPropagationStopped());
        $event->stopPropagation(false);
        $this->assertFalse($event->isPropagationStopped());
    }

    public function test_stop_propagation_default()
    {
        $event = new Event;
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
