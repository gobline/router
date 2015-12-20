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

use ArrayIterator;
use IteratorAggregate;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouteCollection implements IteratorAggregate
{
    private $routes = [];

    public function __call($name, array $arguments)
    {
        $route = $arguments[0];

        if (!$route instanceof RouteInterface) {
            if (strpos($route, ':') !== false) {
                $route = new PlaceholderRoute(...$arguments);
            } else {
                $route = new LiteralRoute(...$arguments);
            }
        }

        $route->allows([strtoupper($name)]);

        return $this->addRoute($route);
    }

    public function addRoute(RouteInterface $route)
    {
        array_unshift($this->routes, $route);

        return $route;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->routes);
    }
}
