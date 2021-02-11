# Daxko API - PHP SDK

This is a wrapper around [Daxko API](https://docs.partners.daxko.com/). The API is very minimal, so this implementation is fairly simple.

## Installation

Install via composer as follows:
```
composer require forward-force/daxko-api-sdk
```

## Usage

### Authentication

Daxko client relies on the `access_token` returned by auth request to
[Daxko authentication endpoint](https://api.partners.daxko.com/auth/token) to
access the API.

Requesting `access_token` using Daxko client:

```php
$apiToken = \ForwardForce\Daxko\Daxko::getToken(
    '<client_id>',
    '<client_secret>',
    '<scope>',
    '<grant_type>'
);
```

The `access_token` returned by `getToken` can then be used to create a new
client instance:

```php
$daxkoClient = new \ForwardForce\Daxko\Daxko($apiToken['access_token']);
```

To avoid requesting a new token everytime a new client is needed, the token can
be stored(encrypted) in some data store(Redis, Session, etc..).

***Refreshing the `access_token` using `refresh_token` from a previous authentication***

```php
$apiToken = Daxko::refreshToken(
    '<client_id>',
    '<refresh_token>',
);
```

### Daxko Classes

***Get all classes for a given location and specific date range:***

```php
$classes = $daxkoClient->classes()
    ->all([
        'startDate' => '<2020-02-09>',
        'endDate' => '<2021-02-09>',
        'locationId' => '<5506>',
    ]);
```

***Get a single class by ID:***

```php
$class = $daxkoClient->classes()->get('<class_id>');
```

Getting the value of a given field is as easy as accessing the `class` instance
property:

```php
$name = $class->name; // the actual class name
```

### Handling errors

All Daxko entities inherit from `DaxkoEntity` class, which provide a
`hasErrors()` and `getErrors()` method that can be used to verify if the
client receive a successful response from the API:

```php
$class = $daxkoClient->classes()->get('<class_id>');

 if ($class->hasErrors()) {
    var_dump($class->getErrors()));
}
```

## Contributions

To run locally, you can use the docker container provided here. You can run it like so:

```
docker-compose up
```

Then you can ssh into the `php-fpm` container. Please note, you need to set your WORKIZ API key and SECRET as 
environmental variables `$DAXKO_TOKEN` and `$DAXKO_SECRET` respectively. However, the secret is not needed at this time
you could set it to anything.

`xdebug` is fully configured to work as cli, hookup your favorite IDE to it and debug away!

There is auto-generated documentation as to how to run this library on local, please  take a look at [phpdocker/README.md](phpdocker/README.md)

*If you find an issue, have a question, or a suggestion, please don't hesitate to open a github issue.*

### Acknowledgments

Thank you to [phpdocker.io](https://phpdocker.io) for making getting PHP environments effortless! 
