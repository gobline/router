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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RouteData
{
    private $routeName;
    private $params;

    public function __construct($routeName, array $params = [])
    {
        $this->routeName = $routeName;
        $this->params = $params;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getParam(...$args)
    {
        switch (count($args)) {
            default:
                throw new \InvalidArgumentException('getParam() takes one or two arguments');
            case 1:
                if (!$this->hasParam($args[0])) {
                    throw new \InvalidArgumentException('Route param "'.$args[0].'" not found');
                }
                return $this->params[$args[0]];
            case 2:
                if (!$this->hasParam($args[0])) {
                    return $args[1];
                }
                return $this->params[$args[0]];
        }
    }

    public function hasParam($name)
    {
        if ((string) $name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        return isset($this->params[$name]);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function setParam($name, $value)
    {
        if ((string) $name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        $this->params[$name] = $value;
    }
}
