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
class PlaceholderRouter extends AbstractPlaceholderRouter
{
    public function __construct($name, $route, array $defaults = [], array $constraints = [])
    {
        parent::__construct($name, $route, $defaults, $constraints);
    }

    public function match(HttpRequestInterface $httpRequest)
    {
        $matcher = new PlaceholderRequestMatcher($this->route, $this->constraints);

        $params = $matcher->match($httpRequest);

        if ($params === false) {
            return false;
        }

        return new RouteData($this->name, $params + $this->defaults);
    }

    public function makeUrl(RouteData $routeData, $language = null, $absolute = false)
    {
        $urlMaker = new PlaceholderUrlMaker($this->route, $this->defaults);

        return $urlMaker->makeUrl($routeData, $language, $absolute);
    }
}
