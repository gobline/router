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
}
