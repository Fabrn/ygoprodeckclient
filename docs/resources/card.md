# Card

This represents an actual Yu-Gi-Oh! card. Data inside this resource will vary according
to the type of the card (link, pendulum, magic, etc.).

## Race

The race of the card depends on its **type**. This will change the instance of the
enum, so you have a better autocompletion with your IDE, and the enum is lighter.

- Monsters : the race will be an instance of `YgoProDeckClient\Enum\Race` and should
be Aqua, Dinosaur, Beast, Warrior, etc.
- Spells : the race will be an instance of `YgoProDeckClient\Enum\SpellType` and
should be Normal, Field, Equip, etc.
- Traps : the race will be an instance of `YgoProDeckClient\Enum\TrapType` and should
be Normal, Continuous or Counter.
- Skills : **the race will be a `string`**. In such case, the race is the name of
the character on the card.

## Links

Link monsters have two specific properties with the `Card` resource : `link` and
`linkMarkers`.

- The `link` property is an `integer` matching the amount of links required.
- The `linkMarkers` property is an array of `YgoProDeckClient\Enum\LinkMarker` 
instances. Each link marker is a side of the link of the card. The enum is just here to
list available sides (Top, Bottom, TopLeft, etc.).

For all other monster types, these two properties will remain respectively `null` and
an empty array.

## Pendulum

Pendulum cards own a specific property named `scale`, which is the actual value of
the Pendulum. This must be an `integer` and will be `null` for other card types.

The `YgoProDeckClient\Enum\Type` enum has specific cases for each type of pendulum
cards. Note that it works the same for the **frame type** with the
`YgoProDeckClient\Enum\FrameType` enum.

If you need to check if a Card is a Pendulum card, you better check if the `scale`
is not `null`.

## Sets

You can retrieve sets in which the card was released using an array of 
`YgoProDeckClient\Model\Set` instances. These instances include the name, code, rarity
(with code) and price of the set.

## Images

You can retrieve **all** existing images of the card in an array of
`YgoProDeckClient\Model\CardImage` available using the `images` property. This is
an array since some cards can have alternative artworks. This array will most often
contain one single element.

Each image owns the following information :
- url : the url of the full version of the card.
- urlSmall : same as url, but smaller.
- urlCropped : only contains the artwork of the card.

### Important note

**Store images on your side**. You may get your IP blacklisted if you spam the image
URLs.