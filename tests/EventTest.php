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
 */
class EventTest extends \PHPUnit\Framework\TestCase
{
    public function test_name()
    {
        $event = new Event($name = 'login');
        $this->assertEquals($name, $event->getName());
    }

    public function test_target()
    {
        $event = new Event('login');
        $event->setTarget($target = 'something');
        $this->assertEquals($target, $event->getTarget());
    }

    public function test_params()
    {
        $event = new Event('login', [
            'id' => $id = 1,
            'email' => $email = 'contact@vaibhavpandey.com',
        ]);
        $this->assertIsArray($event->getParams());
        $this->assertTrue($event->hasParam('id'));
        $this->assertEquals($id, $event->getParam('id'));
        $this->assertTrue($event->hasParam('email'));
        $this->assertEquals($email, $event->getParam('email'));
        $this->assertFalse($event->hasParam('password'));
    }

    public function test_cancellation()
    {
        $event = new Event('login');
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation(true);
        $this->assertTrue($event->isPropagationStopped());
    }

    public function test_get_param_nonexistent()
    {
        $event = new Event('test');
        $this->assertNull($event->getParam('nonexistent'));
    }

    public function test_set_params_replaces_existing()
    {
        $event = new Event('test', ['old' => 'value']);
        $event->setParams(['new' => 'value']);
        $this->assertFalse($event->hasParam('old'));
        $this->assertTrue($event->hasParam('new'));
        $this->assertEquals('value', $event->getParam('new'));
    }

    public function test_set_name()
    {
        $event = new Event('original');
        $event->setName('updated');
        $this->assertEquals('updated', $event->getName());
    }

    public function test_stop_propagation_false()
    {
        $event = new Event('test');
        $event->stopPropagation(true);
        $this->assertTrue($event->isPropagationStopped());
        $event->stopPropagation(false);
        $this->assertFalse($event->isPropagationStopped());
    }

    public function test_stop_propagation_default()
    {
        $event = new Event('test');
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }

    public function test_empty_params()
    {
        $event = new Event('test', []);
        $this->assertIsArray($event->getParams());
        $this->assertEmpty($event->getParams());
    }

    public function test_null_params()
    {
        $event = new Event('test', null);
        $this->assertIsArray($event->getParams());
        $this->assertEmpty($event->getParams());
    }

    public function test_params_with_null_value()
    {
        $event = new Event('test', ['key' => null]);
        $this->assertTrue($event->hasParam('key'));
        $this->assertNull($event->getParam('key'));
    }

    public function test_target_with_object()
    {
        $event = new Event('test');
        $target = new \stdClass;
        $event->setTarget($target);
        $this->assertSame($target, $event->getTarget());
    }

    public function test_target_with_string()
    {
        $event = new Event('test');
        $event->setTarget('string_target');
        $this->assertEquals('string_target', $event->getTarget());
    }

    public function test_target_null()
    {
        $event = new Event('test');
        $this->assertNull($event->getTarget());
        $event->setTarget('something');
        $event->setTarget(null);
        $this->assertNull($event->getTarget());
    }

    public function test_complex_params()
    {
        $event = new Event('test', [
            'string' => 'value',
            'int' => 42,
            'array' => [1, 2, 3],
            'bool' => true,
            'null' => null,
        ]);
        $this->assertEquals('value', $event->getParam('string'));
        $this->assertEquals(42, $event->getParam('int'));
        $this->assertEquals([1, 2, 3], $event->getParam('array'));
        $this->assertTrue($event->getParam('bool'));
        $this->assertNull($event->getParam('null'));
    }
}
