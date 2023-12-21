# Random card client

This API offers the possibility to get a random card.

## Get a random card

Calling the method `generate` will get you a random instance of [`Card`](./../resources/card.md).
No filter can be used.

```php
$card = $client->randomCards->generate();
```