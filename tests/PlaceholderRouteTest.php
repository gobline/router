<?php

use Zend\Diactoros\ServerRequest;
use Gobline\Router\PlaceholderRoute;
use Gobline\Router\RouteData;

class PlaceholderRouteTest extends PHPUnit_Framework_TestCase
{
    public function testPlaceholderRouteMatch()
    {
        $route = (new PlaceholderRoute('foobar', '/foo/:bar(/)(/corge/:qux(/)(/:fred/:waldo+(/)))'))
            ->defaults([
                'bar' => 'apple',
                'qux' => 'pear',
                'fred' => 'cherry',
            ])
            ->constraints([
                'bar' => '[a-zA-Z]+',
                'qux' => '[a-zA-Z]+',
            ]);

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo', 'GET'));
        $this->assertFalse($routeData);

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo/42', 'GET'));
        $this->assertFalse($routeData);

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo/banana/', 'GET'));
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'pear', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/foo/banana', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana']), 'en'));

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo/banana/corge', 'GET'));
        $this->assertFalse($routeData);

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo/banana/corge/orange', 'GET'));
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'orange', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/foo/banana/corge/orange', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana', 'qux' => 'orange'])));

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo/banana/corge/orange/strawberry/raspberry/peach', 'GET'));
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'orange', 'fred' => 'strawberry', 'waldo' => ['raspberry', 'peach']], $routeData->getParams());
        $this->assertSame('/foo/banana/corge/orange/strawberry/raspberry/peach', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana', 'qux' => 'orange', 'fred' => 'strawberry', 'waldo' => ['raspberry', 'peach']])));

        $route = new PlaceholderRoute('foobar', '(/)(/foo/:bar(/))');

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com', 'GET'));
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame([], $routeData->getParams());
        $this->assertSame('', $route->buildUri(new RouteData($route->getName())));

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo', 'GET'));
        $this->assertFalse($routeData);

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/foo/banana/', 'GET'));
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana'], $routeData->getParams());
        $this->assertSame('/foo/banana', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana'])));
    }

    public function testPlaceholderRouteMatchI18n()
    {
        $route = (new PlaceholderRoute('foobar', '/foo/:bar(/)(/corge/:qux(/)(/:fred/:waldo+(/)))'))
            ->defaults([
                'bar' => 'apple',
                'qux' => 'cucumber',
                'fred' => 'green',
            ])
            ->constraints([
                'bar' => '[a-zA-Z]+',
                'qux' => '[a-zA-Z]+',
            ])
            ->i18n([
                'fr' => '/fr/toto/:bar(/)(/titi/:qux(/)(/:fred/:waldo+(/)))',
                'nl' => '/nl/bla/:bar(/)(/blubb/:qux(/)(/:fred/:waldo+(/)))',
                'placeholders' => [
                    'bar' => [
                        'fr' => [
                            'apple' => 'pomme',
                            'banana' => 'banane',
                            'raspberry' => 'framboise',
                        ],
                        'nl' => [
                            'apple' => 'appel',
                            'banana' => 'banana',
                            'raspberry' => 'framboos',
                        ],
                    ],
                    'qux' => [
                        'fr' => [
                            'carrot' => 'carotte',
                            'cucumber' => 'concombre',
                            'tomato' => 'tomate',
                        ],
                        'nl' => [
                            'carrot' => 'wortel',
                            'cucumber' => 'komkommer',
                            'tomato' => 'tomaat',
                        ],
                    ],
                    'fred' => [
                        'fr' => [
                            'blue' => 'bleu',
                            'red' => 'rouge',
                            'green' => 'vert',
                        ],
                        'nl' => [
                            'blue' => 'blauw',
                            'red' => 'rood',
                            'green' => 'groen',
                        ],
                    ],
                    'waldo' => [
                        'fr' => [
                            'monkey' => 'singe',
                            'fish' => 'poisson',
                            'snake' => 'serpent',
                        ],
                        'nl' => [
                            'monkey' => 'aap',
                            'fish' => 'vis',
                            'snake' => 'slang',
                        ],
                    ],
                ],
            ]);

        $request = new ServerRequest([], [], 'http://example.com/fr/toto', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertFalse($routeData);

        $request = new ServerRequest([], [], 'http://example.com/fr/toto/42', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertFalse($routeData);

        $request = new ServerRequest([], [], 'http://example.com/fr/foo/banane', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertFalse($routeData);

        $request = new ServerRequest([], [], 'http://example.com/foo/banana', 'GET');
        $request = $request->withAttribute('_language', 'en');
        $routeData = $route->match($request);
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'cucumber', 'fred' => 'green'], $routeData->getParams());
        $this->assertSame('/foo/banana', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana']), 'en'));

        $request = new ServerRequest([], [], 'http://example.com/fr/toto/banane', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'cucumber', 'fred' => 'green'], $routeData->getParams());
        $this->assertSame('/fr/toto/banane', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana']), 'fr'));

        $request = new ServerRequest([], [], 'http://example.com/fr/toto/banane/titi', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertFalse($routeData);

        $request = new ServerRequest([], [], 'http://example.com/fr/toto/banane/titi/tomato', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'tomato', 'fred' => 'green'], $routeData->getParams());
        $this->assertSame('/fr/toto/banane/titi/tomate', $route->buildUri(new RouteData($route->getName(), ['bar' => 'banana', 'qux' => 'tomato']), 'fr'));

        $request = new ServerRequest([], [], 'http://example.com/fr/toto/framboise/titi/carotte/rouge/singe/serpent', 'GET');
        $request = $request->withAttribute('_language', 'fr');
        $routeData = $route->match($request);
        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getName());
        $this->assertSame(['bar' => 'raspberry', 'qux' => 'carrot', 'fred' => 'red', 'waldo' => ['monkey', 'snake']], $routeData->getParams());
        $this->assertSame('/fr/toto/framboise/titi/carotte/rouge/singe/serpent', $route->buildUri(new RouteData($route->getName(), ['bar' => 'raspberry', 'qux' => 'carrot', 'fred' => 'red', 'waldo' => ['monkey', 'snake']]), 'fr'));
    }
}
