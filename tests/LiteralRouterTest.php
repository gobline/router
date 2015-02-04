<?php

use Mendo\Translator\Translator;
use Mendo\Http\Request\StringHttpRequest;
use Mendo\Http\Request\Resolver\LanguageSubdirectoryResolver;
use Mendo\Router\LiteralRouter;
use Mendo\Router\I18n\LiteralRouter as LiteralI18nRouter;
use Mendo\Router\RouteData;

class LiteralRouterTest extends PHPUnit_Framework_TestCase
{
    public function testLiteralRouterMatch()
    {
        $router = new LiteralRouter(
            'edit-profile', // route name
            '/profile/edit', // route to match
            [
                'controller' => 'profile',
                'action' => 'edit',
            ]
        );

        $routeData = $router->match(new StringHttpRequest('http://example.com/profile/edit'));

        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('edit-profile', $routeData->getRouteName());
        $this->assertSame(['controller' => 'profile', 'action' => 'edit'], $routeData->getParams());
        $this->assertSame('/profile/edit', $router->makeUrl(new RouteData($router->getName(), ['controller' => 'profile', 'action' => 'edit']), 'en'));
    }

    public function testLiteralRouterNoMatch()
    {
        $router = new LiteralRouter(
            'edit-profile',
            '/profile/edit',
            [
                'controller' => 'profile',
                'action' => 'edit',
            ]
        );

        $routeData = $router->match(new StringHttpRequest('http://example.com/profile/add'));

        $this->assertFalse($routeData);
    }

    public function testLiteralI18nRouterMatch()
    {
        $router = new LiteralI18nRouter(
            'edit-profile',
            '/profile/edit',
            [
                'controller' => 'profile',
                'action' => 'edit',
            ]
        );
        $translator = new Translator();
        $translator->addTranslationArray([
            '/profile/edit' => '/profil/modifier',
        ], 'fr');
        $router->setTranslator($translator);

        $httpRequest = new StringHttpRequest('http://example.com/fr/profil/modifier');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);

        $routeData = $router->match($httpRequest);

        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame(['controller' => 'profile', 'action' => 'edit'], $routeData->getParams());
        $this->assertSame('/profile/edit', $router->makeUrl(new RouteData($router->getName(), ['controller' => 'profile', 'action' => 'edit']), 'en'));
        $this->assertSame('/profil/modifier', $router->makeUrl(new RouteData($router->getName(), ['controller' => 'profile', 'action' => 'edit']), 'fr'));
    }

    public function testLiteralI18nRouterNoMatch()
    {
        $router = new LiteralI18nRouter(
            'edit-profile',
            '/profile/edit',
            [
                'controller' => 'profile',
                'action' => 'edit',
            ]
        );
        $translator = new Translator();
        $translator->addTranslationArray([
            '/profile/edit' => '/profil/modifier',
        ], 'fr');
        $router->setTranslator($translator);

        $httpRequest = new StringHttpRequest('http://example.com/fr/profil/ajouter');
        (new LanguageSubdirectoryResolver(['fr', 'en'], 'en'))->resolve($httpRequest);

        $routeData = $router->match($httpRequest);

        $this->assertFalse($routeData);
    }
}
