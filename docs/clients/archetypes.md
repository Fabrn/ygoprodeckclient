# Archetypes client

YgoProDeck owns a public endpoint for *archetypes*, so cards can be associated
to them. Note that this is not official and updated by hand. They take feedback for
card archetypes [here](https://github.com/AlanOC91/YGOPRODeck/issues/10).

## Get all archetypes

The only available endpoint for archetypes is getting them all. This method will return
a list of [Archetype](./../resources/archetype.md) objects :

```php
$response = $client->archetypes->getAll();
```

This list cannot be filtered.

> Archetypes will be sorted A-Z. This is not configurable.