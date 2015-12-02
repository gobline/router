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
use Gobline\Router\Rule\RuleCollection;
use Gobline\Router\Exception\NoMatchingRouteException;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RequestMatcher implements RequestMatcherInterface
{
    private $routes;
    private $rules;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;

        $this->rules = new RuleCollection();
    }

    public function match(ServerRequestInterface $request)
    {
        foreach ($this->routes as $name => $route) {
            if (!$this->rules->isRequestMatchingRouteRules($request, $route)) {
                continue;
            }

            $routeData = $route->match($request);

            if ($routeData) {
                return $routeData;
            }
        }

        throw new NoMatchingRouteException('No matching route for request "'.$request->getUri()->getPath().'"');
    }
}
