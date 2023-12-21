# CardSet

This represents an actual Yu-Gi-Oh! released card set. Unfortunately, the API returns
different data according to the way of reading the set (through list, read or Card data).

## Set

Most of the set data is contained into a `Set` instance within the `CardSet` resource
through the `set` property. A `CardSet` instance will **always** have a `Set` instance,
but the data will change as follows :

- name `string` : always available;
- code `string` : always available;
- rarity `string` : read, Card data;
- price `float` : read, Card data;
- rarityCode `string` : Card data only;
- image `string` : list only.

## Other data

- id `int` : Only available while reading.
- name `string` : Only available while reading.
- numOfCards `int` : Only available through list.
- tcgDate `DateTime` : Release date of the set. Only available through list. 