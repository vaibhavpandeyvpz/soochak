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
 * Class EventListenerQueueTest
 * @package Soochak
 */
class EventListenerQueueTest extends \PHPUnit_Framework_TestCase
{
    public function testOrder()
    {
        $queue = new EventListenerQueue();
        $queue->insert('ABC', 1);
        $queue->insert('DEF', 1);
        $queue->insert('GHI', 1);
        $queue->insert('JKL', 1);
        $queue->insert('MNO', 1);
        $contents = '';
        foreach ($queue as $value) {
            $contents .= $value;
        }
        $this->assertEquals('ABCDEFGHIJKLMNO', $contents);
    }
}
