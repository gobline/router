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
        $path = $data['path'];
        $name = !empty($data['name']) ? $data['name'] : null;
        $i18n = !empty($data['i18n']) ? $data['i18n'] : null;
        $values = !empty($data['values']) ? $data['values'] : [];
        $allows = !empty($data['allows']) ? $data['allows'] : [];
        $allows = is_array($allows) ? $allows : [$allows];

        $route = new LiteralRoute($path);

        if ($name) {
            $route->setName($name);
        }

        $route->values($values)
              ->allows($allows);

        if ($i18n) {
            $route = $route->i18n($i18n);
        }

        return $route;
    }
}
