# Craft 2.1.0 - Docs

## Routing

In your `index.php`, create the app object :

```php
require 'vendor/autoload.php';

$app = new Craft\Web\App([
    '/' => 'My\Logic\Front::hello'
]);

$app->plug();
```

### Arguments in url

It needs the routes you want to use in order to bind an *url* to an *action* (function or `class::method` or `[class, method]`).
You can specify the args you want to catch in the url by prefixing the segment with `:` :

```php
$app = new Craft\Web\App([
    '/some/:arg' => 'My\Logic\Front::hello'
]);
```

The action `My\Logic\Front::hello` will receive `:arg` as the first argument of the method.

### Environment data in url

You can catch and use args that are not meant to be used in the action by prefixing with `+`, for exemple : the current lang.

```php
$app = new Craft\Web\App([
    '/+lang/some/:arg' => 'My\Logic\Front::hello'
]);
```

You can now get the lang value with `Craft\Box\Env::get('lang');`.


## Action

An action can be anything callable :
- a function or `Closure` instance
- a class with `__invoke` method
- a public static method
- a public method

Nothing to extend from, nothing to implement, you are totally free to build your action the way you want to.

### Arguments

As seen in the `Routing` section, url can contains arguments. For example, the router set `/foo/:bar` as a rule and catch
this url : `/foo/5`, the following action will receive `5` as an argument :

```php
namespace My\Logic;

class Front
{
    public function foo($bar)
    {
        echo $bar; // 5
    }
}
```