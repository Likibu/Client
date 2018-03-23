PHP Likibu API Client
=============================

Access more than 5 000 000 short term rentals using [Likibu](http://www.likibu.com) API


### Installing via Composer (Recommended)

The recommended way to install is through [Composer](http://getcomposer.org) :

Install composer
```bash
curl -sS https://getcomposer.org/installer | php
```

Add Likibu Client as dependency
```bash
php composer.phar require likibu/client @dev
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoloader.php'
```

### Using the PHAR archive

Copy LikibuClient.phar to your project's directory.

Then you just have to require it:

```php
require 'path/to/LikibuClient.phar'
```

### Requirements

PHP >= 5.5, cURL, Guzzle 6

You will also need an API key. Contact us on [Likibu](http://www.likibu.com) to get an access.

### [API Documentation](http://api.likibu.com/doc/)

### Exemples

Get all the offers in Paris

```php
<?php
require 'vendor/autoloader.php'

$client = new \Likibu\Client('API_KEY');
$offers = $client->search(array(
    'where' => 'Paris',
    'culture' => 'en',
    'currency' => 'EUR',
));

// do something with the results
foreach ($offers['results'] as $result) {
    $id = $result['id'];
    $title = $result['title'];
}
```

Get an offer's details

```php
<?php
require 'vendor/autoloader.php'

$client = new \Likibu\Client('API_KEY');
$offer = $client->getOffer('azerty123', array(
    'culture' => 'en',
    'currency' => 'EUR',
));
```
