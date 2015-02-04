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

use \IteratorAggregate;
use \ArrayIterator;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouterCollection implements IteratorAggregate
{
    private $routers = [];

    public function add(RouterInterface $router)
    {
        $this->routers = [$router->getName() => $router] + $this->routers;

        return $this;
    }

    public function has($name)
    {
        if ((string) $name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        return isset($this->routers[$name]);
    }

    public function get($name)
    {
        if (!$this->has($name)) {
            throw new \Exception('router "'.$name.'" not found');
        }

        return $this->routers[$name];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->routers);
    }
}
