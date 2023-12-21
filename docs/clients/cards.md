# Card client

The endpoint for getting cards allows quite a lot of filters, so you can more easily
get cards matching your needs. Note that if you request **all** cards without
pagination, the request is pretty **huge**. Because of the amount of filters, cards
can also be queried via **Expressions**.

Expressions were created to make it easier to send bigger queries with combination 
of filters. They also allow more verbose querying, and easy data validation.

## Using Expressions

Before we continue, if you already know [Doctrine expressions](https://www.doctrine-project.org/projects/doctrine-collections/en/stable/expression-builder.html), you
aren't going to be so lost since Expressions were heavily inspired by them.

As for Doctrine, Expressions are constructed using the `Expr` class. Once you get
an instance of `Expr`, you can easily chain methods like so :

```php
Expr::build()
    ->race(Operator::Equals, Race::Dinosaur)
    ->type(Operator::Equals, Type::Fusion)
    ->level(Operator::Greater, 4)
    ->atk(Operator::Greater, 2000)
;
```

This expression says : Fusion Dinosaurs being more than level 4 and having more than
2000 ATK.

As you can see, there is an expression for each available filter. Each **part** have
two required data : an **operator** and a **value**. Operators are available through
the `YgoProDeckClient\Expression\Operator` enum. **Each filter has its set of allowed
operators**. If you use an invalid operator, an `InvalidOperatorException` will be
thrown.

[You can find a full list of available operators for each filter here](./../misc/filter_operators.md)

In order to use Expressions with the client, you must go through the 
`getAllMatchingExpr` method like so :

```php
$response = $client->cards->getAllMatchingExpr(Expr::build()
    ->race(Operator::Equals, Race::Dinosaur)
    ->type(Operator::Equals, Type::Fusion)
    ->level(Operator::Greater, 4)
    ->atk(Operator::Greater, 2000)
);
```