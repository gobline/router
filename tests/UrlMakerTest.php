<?php

use Mendo\Http\Request\StringHttpRequest;
use Mendo\Router\UrlMaker;
use Mendo\Router\RouterCollection;
use Mendo\Router\PlaceholderRouter;
use Mendo\Router\RouteData;

class UrlMakerTest extends PHPUnit_Framework_TestCase
{
    public function testUrlMaker()
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

        $urlMaker = new UrlMaker($routerCollection, new StringHttpRequest('http://example.com/profile/42'));

        $url = $urlMaker->makeUrl(new RouteData('profileUserId', ['id' => 42]));

        $this->assertSame('/profile/42', $url);
    }
}
