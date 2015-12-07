<?php

use Gobline\Router\UriBuilder;
use Gobline\Router\RouteCollection;
use Gobline\Router\PlaceholderRoute;
use Gobline\Router\RouteData;

class UriBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testUriBuilder()
    {
        $routeCollection = new RouteCollection();

        $routeCollection
            ->addRoute(new PlaceholderRoute('profileUsername', '/profile/:username'))
            ->constraints([
                'username' => '[a-zA-Z]+',
            ]);

        $routeCollection
            ->addRoute(new PlaceholderRoute('profileUserId', '/profile/:id'))
            ->constraints([
                'id' => '[0-9]+',
            ]);

        $uriBuilder = new UriBuilder($routeCollection);

        $url = $uriBuilder->buildUri(new RouteData('profileUserId', ['id' => 42]));

        $this->assertSame('/profile/42', $url);
    }
}
