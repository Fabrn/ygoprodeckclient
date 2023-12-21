# Card sets

This API offers the possibility to retrieve and read all released card sets.

## Get all

You can retrieve all card sets using this method in order to get a list of
[`CardSet`](./../resources/card_set.md) objects, which represents the list of all the
current Yu-Gi-Oh! card sets :

```php
$response = $client->cardSets->getAll();
```

This list cannot be filtered.

> Card sets will be sorted A-Z. This is not configurable.

## Find one

You can read a single set by using its **code**. When doing so, a single [`CardSet`](./../resources/card_set.md)
instance will be returned :

```php
$response = $client->cardSets->findOneByCode($code);
```

> A `HttpClientResponseException` will be thrown if the set could not be found.