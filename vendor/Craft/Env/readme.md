# Craft\Env

This package contains tools to interact with session, cookie, flash, authentication and environment data.


## Session

This class is binded to the `$_SESSION` global data.

Store data :

```php
Craft\Env\Session::set('foo', 'bar');
```

Check if data exists :

```php
Craft\Env\Session::has('foo');
```

Retrieve data :

```php
$foo = Craft\Env\Session::get('foo');

# or with fallback
$foo = Craft\Env\Session::get('foo', 'valueIfNotExists');
```

Delete data :

```php
Craft\Env\Session::drop('foo');
```


## Flash

Works the same way as `Session` except the `get()` method that reads **and** drops the content.

```php
$message = flash('form.success'); // alias
```


## Cookie

Binded to `$_COOKIE`, works the same way as `Session` (but not persistent).


## Config

Binded to `$_ENV`, works the same way as `Session`.


## Auth

Use the session but has a different behavior :

Log a user in :

```php
$rank = 5;
$user = 'Babor';

Craft\Env\Auth::login($rank, $user);
```

Log a user out :

```php
Craft\Env\Auth::logout();
```

Check if user is logged in :

```php
if(Craft\Env\Auth::logged()) {

}
```

Check rank (must be logged in) :

```php
$test = 8;
Craft\Env\Auth::allowed($test); // false
```

Get auth data :

```php
$rank = Craft\Env\Auth::rank();
$user = Craft\Env\Auth::user();
```


## Mog

Mog is your best friend, yes really !
It contains all the data you need about $_SERVER, $_POST and $_GET :

POST data :

```php
$all = Craft\Env\Mog::post();
$one = Craft\Env\Mog::post('one');
$one = post('one'); // alias
```

SERVER and GET works the same way as POST.

Tools :

```php
use Craft\Env\Mog;

$ip = Mog::ip();
$referer = Mog::server('HTTP_REFERER');
$lastUrl = Mog::from();
$ajax = Mog::async();
$browser = Mog::browser();
$mobile = Mog::mobile();
```

And a lot more, check the code ;)


## Extend the session

If you want to run your own session, these classes use the following object to bind data :

```php
$session = new Craft\Env\SessionRepository('my.key');
```