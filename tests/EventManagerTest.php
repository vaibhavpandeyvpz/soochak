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
 * @package Soochak
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testTrigger()
    {
        $em = new EventManager();
        $em->attach('dummy', function (EventInterface $event) {
            echo $event->getName();
        });
        $this->expectOutputString('dummy');
        $em->trigger('dummy');
    }

    public function testTriggerWithPriority()
    {
        $em = new EventManager();
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName() . ':10';
        }, 10);
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName() . ':20';
        }, 20);
        $this->expectOutputString('login:20' . 'login:10');
        $em->trigger('login');
    }

    public function testDetach()
    {
        $em = new EventManager();
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName() . ':10';
        });
        $em->attach('login', $detached = function (EventInterface $event) {
            echo $event->getName() . ':20';
        });
        $em->detach('login', $detached);
        $this->expectOutputString('login:10');
        $em->trigger('login');
    }

    public function testStopPropagation()
    {
        $em = new EventManager();
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName() . ':10';
            $event->stopPropagation(true);
        });
        $em->attach('login', function (EventInterface $event) {
            echo $event->getName() . ':20';
        });
        $this->expectOutputString('login:10');
        $em->trigger($event = new Event('login'));
        $this->assertTrue($event->isPropagationStopped());
    }
}
