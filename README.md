# IpBehavior plugin for CakePHP

## Description
An Ip Behavior for the Database Framework of CakePHP, which fills a specified field of an entity with the current client ip taken from the current request.

This plugin should work lovely with the [IpType](https://github.com/TheFRedFox/cakephp-ip-type) plugin.

## Installation
You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

``` php
composer require thefredfox/cakephp-ip-behavior
```

After that you have to load the plugin in your application's bootstrap file and map the type for the database as follows:

``` php
Plugin::load('IpBehavior', ['bootstrap' => true]);
```

In the Table class itself you have to add this behavior:

``` php
// in your Entity Table class (eg. UsersTable)

public function initialize(array $config) {
    //...
    $this->addBehavior('IpBehavior.Ip');
    //...
}
```

The default is, that the behavior will use the field named 'ip', but you can change the configurations like this:

``` php
// in your Entity Table class (eg. UsersTable)

public function initialize(array $config) {
    //...
    $this->addBehavior('IpBehavior.Ip', ['fields' => ['other_ip_field']);
    //...
}
```

and if you want that the ip should always be stored:

``` php
// in your Entity Table class (eg. UsersTable)

public function initialize(array $config) {
    //...
    $this->addBehavior('IpBehavior.Ip', ['fields' => ['ip' => 'always']);
    // respectively
    $this->addBehavior('IpBehavior.Ip', ['fields' => ['other_ip_field' => 'always']);
    //...
}
```

In that way you can store the creators and the modifiers ip:

``` php
// in your Entity Table class (eg. UsersTable)

public function initialize(array $config) {
    //...
    $this->addBehavior('IpBehavior.Ip', ['fields' => ['ip', 'other_ip_field' => 'always']);
    //...
}
```

The 'new' configuration is default and you don't need to explicitly set it.