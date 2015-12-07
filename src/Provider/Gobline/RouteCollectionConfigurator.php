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

use Gobline\Container\ContainerInterface;
use Gobline\Container\ServiceConfiguratorInterface;
use Gobline\Router\Provider\Gobline\LiteralRouteFactory;
use Gobline\Router\Provider\Gobline\PlaceholderRouteFactory;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouteCollectionConfigurator implements ServiceConfiguratorInterface
{
    public function configure($collection, array $config)
    {
        $routes = isset($config['routes']) ? $config['routes'] : [];

        foreach ($routes as $data) {
            $factoryClassName = isset($data['factory']) ? $data['factory'] : null;

            if ($factoryClassName) {
                $factory = new $factoryClassName();
            } elseif (strpos($data['path'], ':') !== false) {
                $factory = new PlaceholderRouteFactory();
            } else {
                $factory = new LiteralRouteFactory();
            }

            $route = $factory($data);

            $collection->addRoute($route);
        }

        return $collection;
    }
}
