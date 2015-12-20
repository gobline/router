<?php

use Zend\Diactoros\ServerRequest;
use Gobline\Router\LiteralRoute;
use Gobline\Router\RouteData;
use Gobline\Router\AbstractRoute;

class LiteralRouteTest extends PHPUnit_Framework_TestCase
{
    public function testLiteralRouteMatch()
    {
        $route = (new LiteralRoute('/profile/edit'))
            ->setName('edit-profile')
            ->controller('profile')
            ->action('edit');

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/profile/edit', 'GET'));

        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame('edit-profile', $routeData->getName());
        $this->assertSame(['_controller' => 'profile', '_action' => 'edit'], $routeData->getParams());
        $this->assertSame('/profile/edit', $route->buildUri(new RouteData($route->getName(), ['controller' => 'profile', 'action' => 'edit']), 'en'));
    }

    public function testLiteralRouteNoMatch()
    {
        $route = (new LiteralRoute('/profile/edit'))
            ->setName('edit-profile')
            ->controller('profile')
            ->action('edit');

        $routeData = $route->match(new ServerRequest([], [], 'http://example.com/profile/add', 'GET'));

        $this->assertFalse($routeData);
    }

    public function testLiteralI18nRouterMatch()
    {
        $route = (new LiteralRoute('/profile/edit'))
            ->setName('edit-profile')
            ->controller('profile')
            ->action('edit')
            ->i18n([
                'fr' => '/fr/profil/modifier',
                'nl' => '/nl/profiel/wijzigen',
            ]);

        $request = new ServerRequest([], [], 'http://example.com/fr/profil/modifier', 'GET');
        $request = $request->withAttribute('_language', 'fr');

        $routeData = $route->match($request);

        $this->assertInstanceOf('Gobline\Router\RouteData', $routeData);
        $this->assertSame(['_controller' => 'profile', '_action' => 'edit'], $routeData->getParams());
        $this->assertSame('/profile/edit', $route->buildUri(new RouteData($route->getName(), ['controller' => 'profile', 'action' => 'edit']), 'en'));
        $this->assertSame('/fr/profil/modifier', $route->buildUri(new RouteData($route->getName(), ['controller' => 'profile', 'action' => 'edit']), 'fr'));
    }

    public function testLiteralI18nRouterNoMatch()
    {
        $route = (new LiteralRoute('/profile/edit'))
            ->setName('edit-profile')
            ->controller('profile')
            ->action('edit')
            ->i18n([
                'fr' => '/fr/profil/modifier',
                'nl' => '/nl/profiel/wijzigen',
            ]);

        $request = new ServerRequest([], [], 'http://example.com/fr/profil/ajouter', 'GET');
        $request = $request->withAttribute('_language', 'fr');

        $routeData = $route->match($request);

        $this->assertFalse($routeData);
    }
}
