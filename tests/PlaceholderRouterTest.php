<?php

use Mendo\Translator\Translator;
use Mendo\Http\Request\StringHttpRequest;
use Mendo\Http\Request\Resolver\LanguageSubdirectoryResolver;
use Mendo\Router\PlaceholderRouter;
use Mendo\Router\I18n\PlaceholderRouter as PlaceholderI18nRouter;
use Mendo\Router\RouteData;

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function testPlaceholderRouterMatch()
    {
        $router = new PlaceholderRouter(
            'foobar',
            '/foo/:bar(/)(/corge/:qux(/)(/:fred/:waldo+(/)))',
            [
                'bar' => 'apple',
                'qux' => 'pear',
                'fred' => 'cherry',
            ],
            [
                'bar' => '[a-zA-Z]+',
                'qux' => '[a-zA-Z]+',
            ]
        );

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo'));
        $this->assertFalse($routeData);

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo/42'));
        $this->assertFalse($routeData);

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo/banana/'));
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'pear', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/foo/banana', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana']), 'en'));

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo/banana/corge'));
        $this->assertFalse($routeData);

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo/banana/corge/orange'));
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'orange', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/foo/banana/corge/orange', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana', 'qux' => 'orange'])));

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo/banana/corge/orange/strawberry/raspberry/peach'));
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'orange', 'fred' => 'strawberry', 'waldo' => ['raspberry', 'peach']], $routeData->getParams());
        $this->assertSame('/foo/banana/corge/orange/strawberry/raspberry/peach', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana', 'qux' => 'orange', 'fred' => 'strawberry', 'waldo' => ['raspberry', 'peach']])));

        $router = new PlaceholderRouter(
            'foobar',
            '(/)(/foo/:bar(/))'
        );

        $routeData = $router->match(new StringHttpRequest('http://example.com'));
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame([], $routeData->getParams());
        $this->assertSame('', $router->makeUrl(new RouteData($router->getName())));

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo'));
        $this->assertFalse($routeData);

        $routeData = $router->match(new StringHttpRequest('http://example.com/foo/banana/'));
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana'], $routeData->getParams());
        $this->assertSame('/foo/banana', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana'])));
    }

    public function testPlaceholderRouterMatchI18n()
    {
        $router = new PlaceholderI18nRouter(
            'foobar',
            '/foo/:bar(/)(/corge/:qux(/)(/:fred/:waldo+(/)))',
            [
                'bar' => 'apple',
                'qux' => 'pear',
                'fred' => 'cherry',
            ],
            [
                'bar' => '[a-zA-Z]+',
                'qux' => '[a-zA-Z]+',
            ],
            ['bar', 'qux', 'fred', 'waldo']
        );

        $translator = new Translator();
        $translator->addTranslationArray([
            '/foo/:bar(/)(/corge/:qux(/)(/:fred/:waldo+(/)))' => '/toto/:bar(/)(/titi/:qux(/)(/:fred/:waldo+(/)))',
            'banana' => 'banane',
            'raspberry' => 'framboise',
            'orange' => 'orange',
            'peach' => 'peche',
            'apple' => 'pomme',
            'pear' => 'poire',
            'strawberry' => 'fraise',
        ], 'fr');
        $router->setTranslator($translator);

        $httpRequest = new StringHttpRequest('http://example.com/fr/toto');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertFalse($routeData);

        $httpRequest = new StringHttpRequest('http://example.com/fr/toto/42');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertFalse($routeData);

        $httpRequest = new StringHttpRequest('http://example.com/fr/foo/banane');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertFalse($routeData);

        $httpRequest = new StringHttpRequest('http://example.com/foo/banana');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'pear', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/foo/banana', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana']), 'en'));

        $httpRequest = new StringHttpRequest('http://example.com/fr/toto/banane');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'pear', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/toto/banane', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana']), 'fr'));

        $httpRequest = new StringHttpRequest('http://example.com/fr/toto/banane/titi');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertFalse($routeData);

        $httpRequest = new StringHttpRequest('http://example.com/fr/toto/banane/titi/orange');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'orange', 'fred' => 'cherry'], $routeData->getParams());
        $this->assertSame('/toto/banane/titi/orange', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana', 'qux' => 'orange']), 'fr'));

        $httpRequest = new StringHttpRequest('http://example.com/fr/toto/banane/titi/orange/fraise/framboise/peche');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);
        $routeData = $router->match($httpRequest);
        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('foobar', $routeData->getRouteName());
        $this->assertSame(['bar' => 'banana', 'qux' => 'orange', 'fred' => 'strawberry', 'waldo' => ['raspberry', 'peach']], $routeData->getParams());
        $this->assertSame('/toto/banane/titi/orange/fraise/framboise/peche', $router->makeUrl(new RouteData($router->getName(), ['bar' => 'banana', 'qux' => 'orange', 'fred' => 'strawberry', 'waldo' => ['raspberry', 'peach']]), 'fr'));
    }
}
