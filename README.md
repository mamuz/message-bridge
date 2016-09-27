Message Bridge
=================

[![Author](http://img.shields.io/badge/author-@mamuz_de-blue.svg?style=flat-square)](https://twitter.com/mamuz_de)
[![Build Status](https://img.shields.io/travis/mamuz/message-bridge.svg?style=flat-square)](https://travis-ci.org/mamuz/message-bridge)
[![Latest Stable Version](https://img.shields.io/packagist/v/mamuz/message-bridge.svg?style=flat-square)](https://packagist.org/packages/mamuz/message-bridge)
[![Total Downloads](https://img.shields.io/packagist/dt/mamuz/message-bridge.svg?style=flat-square)](https://packagist.org/packages/mamuz/message-bridge)
[![License](https://img.shields.io/packagist/l/mamuz/message-bridge.svg?style=flat-square)](https://packagist.org/packages/mamuz/message-bridge)

Message Bridge let you trigger messages from anywhere in a procedural or in an object-oriented way.
A registered callback can dispatch or forward all triggered messages.
Main idea is to support a decoupled event driven and aspect oriented application, thus object awareness to any
logger or any event dispatcher is not needed anymore.

##  Installation

Install the latest version with

```sh
$ composer require mamuz/message-bridge
```

## Example

### Procedural

```php
// Register dispatch callback globally
set_message_dispatcher(function ($msg, $argv, $emitter) {
    if ($msg == 'user.registered') {
        mail('foo@bar.com', 'A new user entered', 'UserId ' . $argv['userId']);
    }
});

// Trigger any message anywhere
trigger_message('user.registered', array('userId' => 1234));
```

### Object oriented way with forwarding

```php
$bridge = \MsgBridge\MessageBridge::getInstance();
$bridge->bindDispatcher(function ($msg, $argv, $emitter) use ($eventManager) {
    $eventManager->trigger($msg, $argv, $emitter);
});

// ...

$bridge->trigger('user.registered', array('userId' => 1234));
```

## Locking concept and Test Isolation

To prevent side-effects the dispatcher can be registered with write protection.

```php
$locked = true;
set_message_dispatcher($closure, $locked);

// This will throw a RuntimeException now
set_message_dispatcher($anotherClosure);
```

In Unit Tests you should not register a dispatcher with write protection,
otherwise test isolation is not given.
Instead of that implement following snippet to the tearDown method.

```php
public function tearDown()
{
    \MsgBridge\MessageBridge::getInstance()->unsetDispatcher();
}
```

As an alternative you can add the provided TestListener to your phpunit.xml.
This Listener will do the job for you automaticly.

```xml
<phpunit>
    <listeners>
        <listener class="\MsgBridge\TestListener"></listener>
    </listeners>
</phpunit>
```
