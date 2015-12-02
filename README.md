# PSR-7 Router component

The purpose of routing is to **map a URL to an array of data or to a callback**, allowing to **route the request to the correct resource**.
In an MVC context, this is typically a controller and a controller action.

There are two routers bundled by default:

* `Gobline\Router\LiteralRoute` (with its i18n friend `Gobline\Router\I18n\LiteralRoute`)
* `Gobline\Router\PlaceHolderRoute` (with its i18n friend `Gobline\Router\I18n\PlaceHolderRoute`)

The Router component allows to have your **URLs translated in multiple languages**, allowing to have more SEO- and user-friendly URLs.

## LiteralRoute

The Literal route is for doing exact matching of the URI path.

```php
$router = (new Gobline\Router\LiteralRoute('profile', '/user/profile')) // profile is the route name and /user/profile is the route to match
    ->values([
        'controller' => 'user',
        'action' => 'profile',
    ]);
```

## I18n LiteralRoute

```php
$router = (new Gobline\Router\LiteralRoute('profile', '/user/profile'))
    ->values([
        'controller' => 'user',
        'action' => 'profile',
    ])
    ->i18n([
        'fr' => '/membre/profil',
        'nl' => '/gebruiker/profiel',
    ]);
```

## PlaceHolderRouter

```php
$router = (new Gobline\Router\PlaceHolderRoute('profile', '/user/:id(/)(/articles/:action(/))'))
    ->values([
        'controller' => 'articles',
        'action' => 'list',
    ])
    ->constraints([
        'id' => '[0-9]+',
        'action' => '[a-zA-Z]+',
    ]);
```

## I18n PlaceHolderRouter

```php
$router = (new Gobline\Router\PlaceHolderRoute('profile', '/user/:id(/)(/articles/:action(/))'))
    ->values([
        'controller' => 'articles',
        'action' => 'list',
    ])
    ->constraints([
        'id' => '[0-9]+',
        'action' => '[a-zA-Z]+',
    ])
    ->i18n([
        'fr' => '/membre/:id(/)(/articles/:action(/))',
        'nl' => '/gebruiker/:id(/)(/artikelen/:action(/))',
        'placeholders' => [
            'action' => [
                'fr' => [
                    'list' => 'liste',
                ],
                'nl' => [
                    'list' => 'lijst',
                ],
            ],
        ],
    ]);
```

## Matching a URL to a Collection of Routes

```php
$routeCollection = new Gobline\Router\RouteCollection();
$routeCollection
    ->get(new Gobline\Router\LiteralRoute(/*...*/))
    ->post(new Gobline\Router\PlaceHolderRouter(/*...*/));

$requestMatcher = new Gobline\Router\RequestMatcher($routeCollection);
$routeData = $requestMatcher->match($request); // psr-7 server request
```

## Generating a URL Based on Route Data

```php
$uriBuilder = new Gobline\Router\UriBuilder($routerCollection);

$url = $uriBuilder->makeUrl(new Gobline\Router\RouteData('profile', ['id' => 42]));
```

## Installation

You can install the Router component using the dependency management tool [Composer](https://getcomposer.org/).
Run the *require* command to resolve and download the dependencies:

```
composer require gobline/router
```