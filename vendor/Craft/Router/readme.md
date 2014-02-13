# Craft\Router

A router is a component that resolve a path between a query and its target using specific rules.

The logic is shared between the `RouteProvider` that contains the routes and the `Matcher` object
that will parse and resolve the query :

```
          rules
            |
query -> [router] -> target
```


### UrlMatcher

In this example, we'll use the UrlMatcher used by the Craft\Kernel component.
First, create your rules :

```php
$routes = new Craft\Router\RouteProvider([
    '/'    => 'Front::index',
    '/foo' => 'Front::foo',
    '/bar' => 'Front::bar'
]);
```

The rule are the url, and the target are the action.

Now, give this to your `UrlMatcher` :

```php
$matcher = new Craft\Router\Matcher\UrlMatcher($routes);
```

And try to find something with a query, if the router match a result, it will return a `Route` object
that contains the name, the rule, the target and the optional data.

```php
$result = $matcher->find('/boo'); // false
$result = $matcher->find('/foo'); // Route object
```


### Create your own matcher

You can create your own matcher by extending `Craft\Router\Matcher`.