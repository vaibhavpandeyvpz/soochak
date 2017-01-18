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
 * Class EventTest
 * @package Soochak
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $event = new Event($name = 'login');
        $this->assertEquals($name, $event->getName());
    }

    public function testTarget()
    {
        $event = new Event('login');
        $event->setTarget($target = 'something');
        $this->assertEquals($target, $event->getTarget());
    }

    public function testParams()
    {
        $event = new Event('login', array(
            'id' => $id = 1,
            'email' => $email = 'contact@vaibhavpandey.com'
        ));
        $this->assertInternalType('array', $event->getParams());
        $this->assertTrue($event->hasParam('id'));
        $this->assertEquals($id, $event->getParam('id'));
        $this->assertTrue($event->hasParam('email'));
        $this->assertEquals($email, $event->getParam('email'));
        $this->assertFalse($event->hasParam('password'));
    }

    public function testCancellation()
    {
        $event = new Event('login');
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation(true);
        $this->assertTrue($event->isPropagationStopped());
    }
}
