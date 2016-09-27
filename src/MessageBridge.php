<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 Marco Muths
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

declare(strict_types = 1);

namespace MsgBridge;

class MessageBridge
{
    /** @var self */
    private static $instance;

    /** @var callable|null */
    private $dispatcher;

    /** @var bool */
    private $dispatcherIsLocked = false;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @param callable $dispatcher
     * @param bool     $lock
     * @throws \RuntimeException
     */
    public function bindDispatcher(callable $dispatcher, bool $lock = false)
    {
        if ($this->dispatcher && $this->dispatcherIsLocked) {
            throw new \RuntimeException('MessageDispatcher is already set and locked');
        }

        $this->dispatcher = $dispatcher;
        $this->dispatcherIsLocked = $lock;
    }

    /**
     * @return callable|null
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @throws \RuntimeException
     */
    public function unsetDispatcher()
    {
        if ($this->dispatcherIsLocked) {
            throw new \RuntimeException('MessageDispatcher is locked');
        }

        $this->dispatcher = null;
    }

    /**
     * @param string $name
     * @param array  $argv
     * @param mixed  $emitter
     * @return mixed
     */
    public function trigger(string $name, array $argv = array(), $emitter = null)
    {
        if (!$this->getDispatcher()) {
            return false;
        }

        $callable = $this->getDispatcher();

        return $callable($name, $argv, $emitter);
    }
}
