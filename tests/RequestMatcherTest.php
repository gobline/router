<?php

use Zend\Diactoros\ServerRequest;
use Gobline\Router\RequestMatcher;
use Gobline\Router\RouteCollection;
use Gobline\Router\PlaceholderRoute;

class RequestMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testRequestMatcher()
    {
        $routeCollection = new RouteCollection();

        $routeCollection
            ->get(new PlaceholderRoute('profileUsername', '/profile/:username'))
            ->constraints([
                'username' => '[a-zA-Z]+',
            ]);

        $routeCollection
            ->addRoute(new PlaceholderRoute('profileUserId', '/profile/:id'))
            ->constraints([
                'id' => '[0-9]+',
            ])
            ->allows(['GET', 'POST']);

        $requestMatcher = new RequestMatcher($routeCollection);

        $routeData = $requestMatcher->match(new ServerRequest([], [], 'http://example.com/profile/42', 'POST'));

        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('profileUserId', $routeData->getName());

        $routeData = $requestMatcher->match(new ServerRequest([], [], 'http://example.com/profile/foobar', 'GET'));

        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('profileUsername', $routeData->getName());
    }
}
