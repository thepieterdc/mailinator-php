# mailinator_php

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/brnlbs/mailinator/blob/master/LICENSE)

PHP wrapper for the Mailinator.com API

## Token
Create a [Mailinator](http://www.mailinator.com) account, login, and find your token at [https://www.mailinator.com/settings.jsp](https://www.mailinator.com/settings.jsp)

## Requirements
You need to have the [cURL](http://php.net/manual/en/book.curl.php)-extension installed on your server. [PHP](http://www.php.net) 5.2 will suffice.

## Usage
``` php
require 'Mailinator.php';
$mailinator = new Mailinator('my_token');

//Get messages in inbox//
print_r($mailinator->fetchInbox('randominbox'));

//Get the id by running fetchInbox() first//
print_r($mailinator->fetchMail('mail-id'));
```

## License

The MIT License (MIT). Please see [License File](https://github.com/thepieterdc/mailinator_php/blob/master/LICENSE) for more information.