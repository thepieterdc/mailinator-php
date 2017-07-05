# mailinator_php

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/brnlbs/mailinator/blob/master/LICENSE)
[![Monthly downloads](https://img.shields.io/packagist/dm/thepieterdc/mailinator_php.svg)](https://packagist.org/packages/thepieterdc/mailinator_php)[![Daily downloads](https://img.shields.io/packagist/dd/thepieterdc/mailinator_php.svg)](https://packagist.org/packages/thepieterdc/mailinator_php)[![Total downloads](https://img.shields.io/packagist/dt/thepieterdc/mailinator_php.svg)](https://packagist.org/packages/thepieterdc/mailinator_php)

PHP wrapper for the Mailinator.com API

## Token
Create a [Mailinator](http://www.mailinator.com) account, login, and find your token at [https://www.mailinator.com/settings.jsp](https://www.mailinator.com/settings.jsp)

## Requirements
You need to have the [cURL](http://php.net/manual/en/book.curl.php)-extension installed on your server. [PHP](http://www.php.net) 5.2 will suffice.

## Usage
``` php
require_once __DIR__ . '/vendor/autoload.php';

use Mailinator\Mailinator;

$mailinator = new Mailinator('my_token');

//Get messages in inbox//
try
{
    print_r($mailinator->inbox('randominbox'));
} catch(Exception $e) {
    // Process the error
    echo "Something went wrong: " . $e->getMessage();
}

//Get a message//
try
{
    print_r($mailinator->message('mail-id'));
} catch(Exception $e) {
    // Process the error
    echo "Something went wrong: " . $e->getMessage();
}

//Delete a message//
try
{
    print_r($mailinator->delete('mail-id'));
} catch(Exception $e) {
    // Process the error
    echo "Something went wrong: " . $e->getMessage();
}
```

## License

The MIT License (MIT). Please see [License File](https://github.com/thepieterdc/mailinator_php/blob/master/LICENSE.txt) for more information.
