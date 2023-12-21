# YgoProDeck API PHP Client

Modern PHP client for the [YgoProDeck API](https://ygoprodeck.com/api-guide/) using PHP
8.1 and cURL.

## Install using Composer

```shell
composer require fabrn/ygoprodeckclient
```

## Using the client

The main entrypoint for the client is the `YgoProDeckClient\Client` class. From this
point, you can access resource-specific clients using **public properties** :

```php
$client = new Client();

$response = $client->cards->getAll();
```

If you give no parameter for the `Client` instance, you get it with default parameters,
which are :
- Language : en
- Version : v7 (note that this client has be designed for v7)

In order to configure yourself the client, just give the configuration using an array
to the `Client` instance :

```php
$client = new Client([
    Client::PARAM_LANGUAGE => Language::French->value /* fr */,
    Client::PARAM_API_VERSION => 'v7'
]);
```

Each available language can be accessed with the `YgoProDeckClient\Enum\Language` enum.
These are :
- French
- Portuguese
- German
- Italian

> Note that card images will remain in English.

## Resource clients

- [Cards](./docs/clients/cards.md)
- [Card sets](./docs/clients/card_sets.md)
- [Archetypes](./docs/clients/archetypes.md)
- [Random cards](./docs/clients/random_cards.md)

## Rate limiting

This API sets a rate limit to **20 requests per 1 second**. This client does not
support rate limiting *yet*. Make sure to not exceed this rate limit, otherwise **your
IP may get blacklisted**.

## Important note on card images

As you will see by using the API, card images are delivered using the URL
"https://images.ygoprodeck.com/images/". As mentioned on the documentation, please
**store images on your side** in order not to flood their website. Otherwise you
may also **get your IP blacklisted**.

## License and legal notice

This package is available under [MIT license](https://choosealicense.com/licenses/mit/).

Also note that YgoProDeckClient isn't endorsed by Konami or event YgoProDeck and 
doesn't reflect the views or opinions of neither of them. Every information available
through this client, including card images, the attribute, level/rank and type symbols, 
and card text, is copyrighted by 4K Media Inc, a subsidiary of Konami Digital 
Entertainment, Inc.