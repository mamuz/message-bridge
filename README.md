Message Bridge
=================

Message Bridge let you trigger messages from anywhere in a procedural or in a object-oriented way.
A registered callback can dispatch all triggered messages.
Main idea is to support a decoupled event driven application.

## Installation

The recommended way to install
[`mamuz/message-bridge`](https://packagist.org/packages/mamuz/message-bridge) is through
[composer](http://getcomposer.org/) by adding dependency to your `composer.json`:

```json
{
    "require-dev": {
        "mamuz/message-bridge": "0.*"
    }
}
```

## Example

### Register dispatching callback globally

```php
setMessageDispatcher(function ($msg, $argv, $emitter) {
    if ($msg == 'user.registered') {
        mail('foo@bar.com', 'A new user entered', 'UserId ' . $argv['userId']);
    }
});
```

### Trigger any message anywhere

```php
triggerMessage('user.registered', array('userId' => 1234));
```
