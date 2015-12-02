<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class UriBuilder implements UriBuilderInterface
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        $path = $this->routes->getRoute($routeData->getName())->buildUri($routeData, $language);

        return $path;
    }

    public function buildUrl(RouteData $routeData, $language = null)
    {
        return $this->buildUri($routeData, $language);
    }
}
