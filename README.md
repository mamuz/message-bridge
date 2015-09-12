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

## Installation

The recommended way to install
[`mamuz/message-bridge`](https://packagist.org/packages/mamuz/message-bridge) is through
[composer](http://getcomposer.org/) by adding dependency to your `composer.json`:

```json
{
    "require": {
        "mamuz/message-bridge": "0.*"
    }
}
```

## Example

### Procedural

```php
// Register dispatch callback globally
setMessageDispatcher(function ($msg, $argv, $emitter) {
    if ($msg == 'user.registered') {
        mail('foo@bar.com', 'A new user entered', 'UserId ' . $argv['userId']);
    }
});

// Trigger any message anywhere
triggerMessage('user.registered', array('userId' => 1234));
```

### Object oriented way with forwarding

```php
$bridge = \MsgBridge\MessageBridge::getInstance();
$bridge->setDispatcher(function ($msg, $argv, $emitter) use ($eventManager) {
    $eventManager->trigger($msg, $argv, $emitter);
});

// ...

$bridge->trigger('user.registered', array('userId' => 1234));
```
