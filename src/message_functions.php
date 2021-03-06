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

if (!function_exists('set_message_dispatcher')) {
    /**
     * @param callable $dispatcher
     * @param bool     $lock
     * @return callable|null
     * @throws \RuntimeException
     */
    function set_message_dispatcher(callable $dispatcher, bool $lock = false)
    {
        $messageHandler = \MsgBridge\MessageBridge::getInstance();
        $existingDispatcher = $messageHandler->getDispatcher();
        $messageHandler->bindDispatcher($dispatcher, $lock);

        return $existingDispatcher;
    }
}

if (!function_exists('trigger_message')) {
    /**
     * @param string $name
     * @param array  $argv
     * @param mixed  $emitter
     * @return mixed
     */
    function trigger_message(string $name, array $argv = array(), $emitter = null)
    {
        $messageHandler = \MsgBridge\MessageBridge::getInstance();
        return $messageHandler->trigger($name, $argv, $emitter);
    }
}
