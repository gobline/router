<?php

use Mendo\Http\Request\StringHttpRequest;
use Mendo\Router\RequestMatcher;
use Mendo\Router\RouterCollection;
use Mendo\Router\PlaceholderRouter;

class RequestMatcherTest extends PHPUnit_Framework_TestCase
{
    public function testRequestMatcher()
    {
        $routerCollection = new RouterCollection();

        $routerCollection
            ->add(new PlaceholderRouter(
                'profileUsername',
                '/profile/:username',
                [],
                [
                    'username' => '[a-zA-Z]+',
                ]
            ))
            ->add(new PlaceholderRouter(
                'profileUserId',
                '/profile/:id',
                [],
                [
                    'id' => '[0-9]+',
                ]
            ));

        $requestMatcher = new RequestMatcher($routerCollection);

        $routeData = $requestMatcher->match(new StringHttpRequest('http://example.com/profile/42'));

        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('profileUserId', $routeData->getRouteName());

        $routeData = $requestMatcher->match(new StringHttpRequest('http://example.com/profile/foobar'));

        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('profileUsername', $routeData->getRouteName());
    }
}
