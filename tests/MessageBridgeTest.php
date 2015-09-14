<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Marco Muths
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace MsgBridgeTest;

use MsgBridge\MessageBridge;

class MessageBridgeTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Closure */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = function ($name, $argv, $emitter) {
            return array($name, $argv, $emitter);
        };
    }

    public function testTriggerMessageWithoutDispatcher()
    {
        $this->assertFalse(trigger_message('Foo'));
    }

    public function testBindDispatcherUnlocked()
    {
        $this->assertNull(set_message_dispatcher($this->dispatcher));
        $this->assertSame($this->dispatcher, set_message_dispatcher(clone $this->dispatcher));
    }

    public function testUnsetUnlockedDispatcher()
    {
        set_message_dispatcher($this->dispatcher);
        $this->assertNotFalse(trigger_message('Foo'));
        MessageBridge::getInstance()->unsetDispatcher();
        $this->assertFalse(trigger_message('Foo'));
    }

    public function testBindDispatcherLocked()
    {
        set_message_dispatcher($this->dispatcher, true);
        $this->setExpectedException('RuntimeException');
        set_message_dispatcher($this->dispatcher);
    }

    /**
     * @depends testBindDispatcherLocked
     */
    public function testTriggerMessage()
    {
        $name = 'foo';
        $argv = array('foo' => 'bar');
        $emitter = $this;
        $result = trigger_message($name, $argv, $emitter);

        $this->assertSame(array($name, $argv, $emitter), $result);
    }
}
