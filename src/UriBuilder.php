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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class UriBuilder implements UriBuilderInterface
{
    private $routes = [];

    public function __construct(RouteCollection $routes)
    {
        foreach ($routes as $route) {
            if (!$route->getName()) {
                continue;
            }

            $this->routes[$route->getName()] = $route;
        }
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        $name = $routeData->getName();

        if (!isset($this->routes[$name])) {
            throw new \Exception('route "'.$name.'" not found');
        }

        $path = $this->routes[$name]->buildUri($routeData, $language);

        return $path;
    }

    public function buildUrl(RouteData $routeData, $language = null)
    {
        return $this->buildUri($routeData, $language);
    }
}
