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
class LiteralRouter extends AbstractLiteralRouter
{
    public function match(HttpRequestInterface $httpRequest)
    {
        if ($httpRequest->getPath() !== $this->route) {
            return false;
        }

        return new RouteData($this->name, $this->params);
    }

    public function makeUrl(RouteData $routeData, $language = null)
    {
        return $this->route;
    }
}
