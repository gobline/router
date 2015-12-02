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

use Gobline\Router\PlaceholderRoute;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class PlaceholderRouteFactory
{
    public function __invoke(array $data)
    {
        $name = $data['name'];
        $path = $data['path'];
        $values = !empty($data['values']) ? $data['values'] : [];
        $allows = !empty($data['allows']) ? $data['allows'] : [];
        $allows = is_array($allows) ? $allows : [$allows];
        $defaults = !empty($data['defaults']) ? $data['defaults'] : [];
        $constraints = !empty($data['constraints']) ? $data['constraints'] : [];

        $route = new PlaceholderRoute($name, $path);

        $route->values($values)
              ->allows($allows)
              ->defaults($defaults)
              ->constraints($constraints);

        return $route;
    }
}
