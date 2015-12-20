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
        $defaultData = isset($config['default']) ? $config['default'] : [];

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

            $data = $this->array_merge_recursive_distinct($defaultData, $data);

            $route = $factory($data);

            $collection->addRoute($route);
        }

        return $collection;
    }

    private function array_merge_recursive_distinct(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
