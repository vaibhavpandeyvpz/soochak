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
 * Class EventListenerQueueTest
 */
class EventListenerQueueTest extends \PHPUnit\Framework\TestCase
{
    public function test_order()
    {
        $queue = new EventListenerQueue;
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

    public function test_priority_ordering()
    {
        $queue = new EventListenerQueue;
        $queue->insert('low', 1);
        $queue->insert('high', 10);
        $queue->insert('medium', 5);
        $queue->insert('highest', 20);
        $results = [];
        foreach ($queue as $value) {
            $results[] = $value;
        }
        $this->assertEquals(['highest', 'high', 'medium', 'low'], $results);
    }

    public function test_mixed_priority_same_value()
    {
        $queue = new EventListenerQueue;
        $queue->insert('first', 5);
        $queue->insert('second', 5);
        $queue->insert('third', 5);
        $results = [];
        foreach ($queue as $value) {
            $results[] = $value;
        }
        $this->assertEquals(['first', 'second', 'third'], $results);
    }

    public function test_negative_priority()
    {
        $queue = new EventListenerQueue;
        $queue->insert('negative', -10);
        $queue->insert('zero', 0);
        $queue->insert('positive', 10);
        $results = [];
        foreach ($queue as $value) {
            $results[] = $value;
        }
        $this->assertEquals(['positive', 'zero', 'negative'], $results);
    }

    public function test_array_priority()
    {
        $queue = new EventListenerQueue;
        $queue->insert('test', [10, 100]);
        $value = $queue->extract();
        $this->assertEquals('test', $value);
    }
}
