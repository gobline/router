# Router Component - Mendo Framework

The purpose of routing is to **map a URL to an array of data** allowing to **route the request to the correct resource**.
In an MVC context, this is typically a controller and a controller action.

There are two routers bundled by default:

* `Mendo\Router\LiteralRouter` (with its i18n friend `Mendo\Router\I18n\LiteralRouter`)
* `Mendo\Router\PlaceHolderRouter` (with its i18n friend `Mendo\Router\I18n\PlaceHolderRouter`)

The Mendo Router component allows to have your **URLs translated in multiple languages**, allowing to have more SEO- and user-friendly URLs.
This is acheived by injecting a ```Mendo\Translator\TranslatorInterface``` instance into an i18n router.
When matching for a route, the language is retrieved from the ```Mendo\Http\HttpRequestInterface``` instance (see below).

## LiteralRouter

The Literal route is for doing exact matching of the URI path.

```php
$router = new Mendo\Router\LiteralRouter(
    'profile', // route name
    'user/profile', // route to match
    [
        'controller' => 'user',
        'action' => 'profile',
    ]
);
```

## I18n LiteralRouter

```php
$router = new Mendo\Router\LiteralRouter(
    'profile', // route name
    'user/profile', // route to match
    [
        'controller' => 'user',
        'action' => 'profile',
    ]
);

$translator = new Mendo\Translator\Translator();
$translator->addTranslationArray([
    '/user/profile' => '/membre/profil',
], 'fr');
$router->setTranslator($translator);
```

## PlaceHolderRouter

```php
$router = new Mendo\Router\PlaceHolderRouter(
    'profile',
    '/user/:id(/)(/articles/:action(/))',
    [
        'action' => 'list',
    ],
    [
        'id' => '[0-9]+',
        'action' => '[a-zA-Z]+',
    ]
);
```

## I18n PlaceHolderRouter

```php
$router = new Mendo\Router\PlaceHolderRouter(
    'profile',
    '/user/:id(/)(/articles/:action(/))',
    [
        'action' => 'list',
    ],
    [
        'id' => '[0-9]+',
        'action' => '[a-zA-Z]+',
    ],
    ['action'] // parameter value to translate
);

$translator = new Mendo\Translator\Translator();
$translator->addTranslationArray([
    '/user/:id(/)(/articles/:action(/))' => '/membre/:id(/)(/articles/:action(/))',
    'list' => 'liste',
    // ...
], 'fr');
$router->setTranslator($translator);
```

## Matching a URL to a Collection of Routes

```php
$routerCollection = new Mendo\Router\RouterCollection();
$routerCollection
    ->add(new Mendo\Router\LiteralRouter(/*...*/))
    ->add(new Mendo\Router\LiteralRouter(/*...*/))
    ->add(new Mendo\Router\PlaceHolderRouter(/*...*/))
    ->add(new Mendo\Router\PlaceHolderRouter(/*...*/))
    ->add(new Mendo\Router\PlaceHolderRouter(/*...*/));

$requestMatcher = new Mendo\Router\RequestMatcher($routerCollection);
$routeData = $requestMatcher->match($httpRequest); // see mendo/http component
```

## Generating a URL Based on Route Data

```php
$urlMaker = new Mendo\Router\UrlMaker($routerCollection, $httpRequest); // see mendo/http component

$url = $urlMaker->makeUrl(new Mendo\Router\RouteData('profile', ['id' => 42]));
```

## Installation

You can install Mendo Router using the dependency management tool [Composer](https://getcomposer.org/).
Run the *require* command to resolve and download the dependencies:

```
composer require mendoframework/router
```