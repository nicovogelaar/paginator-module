Paginator module
=======

Introduction
------------

This paginator module is intended to easily implement pagination with sorting and filters in your application.

Currently the paginator only supports the Doctrine QueryBuilder.

Usage
------------
* [Example](https://github.com/nicovogelaar/paginator-module/blob/master/docs/example.md)

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2)

Installation
------------

#### Install with composer

```sh
./composer.phar require nicovogelaar/paginator-module
#when asked for a version, type "*".
```

#### Enable module

Enabling the module in your `application.config.php` file.


```php
<?php
return array(
    'modules' => array(
        // ...
        'Paginator',
    ),
    // ...
);
```
