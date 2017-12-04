Assert email
==============
[![Build Status](https://travis-ci.org/albertcolom/assert-email.svg?branch=master)](https://travis-ci.org/albertcolom/assert-email)
[![License MIT](http://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/albertcolom/assert-email/blob/master/LICENSE)

PHP >= 5.4

PHP library to check email inspired in [webmozart/assert](https://packagist.org/packages/webmozart/assert)

## Installation

```php
composer require albertcolom/assert-email

```

## Example usage

```php
use albertcolom\Assert\AssertEmail;

class User
{
    //...
    
    public function setEmail(string $email)
    {
        AssertEmail::valid($email);
    }
}

```

```php

$user = new User;

$user->setEmail('foo@domain.com'); // true
$user->setEmail('foo@domain'); // InvalidArgumentException: Invalid email "foo@domain"
```

## Assertions

#### Valid
Check email valid RFC 2822
```php
valid($email, $message = '')

AssertEmail::valid('foo@domain.com'); // true
AssertEmail::valid('foo@domain'); // InvalidArgumentException: Invalid email "foo@domain"
AssertEmail::valid('foo@domain', 'Custom message %s'); // InvalidArgumentException: Custom message "foo@domain"
```

#### Temporal mail
Check temporary emails, it provides a built-in database of [2000+](https://github.com/albertcolom/assert-email/blob/master/resources/temporal-mail-domain.txt) domains
```php
temporalMail($email, $message = '')`

AssertEmail::temporalMail('foo@domain.com'); // true
AssertEmail::temporalMail('foo@yopmail.com'); // InvalidArgumentException: Temporal email is not allowed "test@yopmail.com"
AssertEmail::temporalMail('foo@yopmail.com', 'Custom message %s'); // InvalidArgumentException: Custom message "foo@domain"
```

#### DNS
Check DNS MX registry
```php
dns($email, $message = '')

AssertEmail::dns('foo@domain.com'); // true
AssertEmail::dns('foo@domain.000'); // InvalidArgumentException: Incorrect domain name "domain.000"
AssertEmail::dns('foo@domain.000', 'Custom message %s'); // InvalidArgumentException: Custom message "domain.000"
```

#### Domains Allowed
Check if domain allowed list
```php
domainsAllowed($email, array $domains, $message = '')

$allowed = ['mysite.com', 'somedomain.xy', 'test.dev'];

AssertEmail::domainsAllowed('foo@test.dev', $allowed); // true
AssertEmail::domainsAllowed('foo@gmail.com', $allowed); // InvalidArgumentException: Domain is not allowed "foo@gmail.com"
AssertEmail::domainsAllowed('foo@gmail.com', $allowed, 'Custom message %s'); // InvalidArgumentException: Custom message "foo@gmail.com"
```