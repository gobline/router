<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router\Rule;

use Psr\Http\Message\ServerRequestInterface;
use Gobline\Router\RouteInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Method implements RuleInterface
{
    public function match(ServerRequestInterface $request, RouteInterface $route)
    {
        $requestMethod = $request->getMethod() ?: 'GET';

        return $route->isRequestMethodAllowed($requestMethod);
    }
}
