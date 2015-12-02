<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router\Provider\Gobline;

use Gobline\Router\LiteralRoute;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class LiteralRouteFactory
{
    public function __invoke(array $data)
    {
        $name = $data['name'];
        $path = $data['path'];
        $values = !empty($data['values']) ? $data['values'] : [];
        $allows = !empty($data['allows']) ? $data['allows'] : [];
        $allows = is_array($allows) ? $allows : [$allows];

        $route = new LiteralRoute($name, $path);

        $route->values($values)
              ->allows($allows);

        return $route;
    }
}
