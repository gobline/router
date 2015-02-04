<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router;

use Mendo\Http\Request\HttpRequestInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RequestMatcher implements RequestMatcherInterface
{
    private $routers;

    public function __construct(RouterCollection $routers)
    {
        $this->routers = $routers;
    }

    public function match(HttpRequestInterface $httpRequest)
    {
        foreach ($this->routers as $route => $router) {
            $routeData = $router->match($httpRequest);

            if ($routeData) {
                return $routeData;
            }
        }

        throw new \RuntimeException('No matching route for request "'.$httpRequest->getUrl(true).'"');
    }
}
