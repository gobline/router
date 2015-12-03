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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouteCollectionConfigurator implements ServiceConfiguratorInterface
{
    public function configure($collection, array $config, ContainerInterface $container)
    {
        $routes = isset($config['routes']) ? $config['routes'] : [];

        foreach ($routes as $data) {
            $factoryClassName = $data['factory'];

            $factory = new $factoryClassName();

            $route = $factory($data);

            $collection->addRoute($route);
        }

        return $collection;
    }
}
