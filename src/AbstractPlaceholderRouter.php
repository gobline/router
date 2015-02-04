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
abstract class AbstractPlaceholderRouter extends AbstractRouter
{
    protected $route;
    protected $defaults;
    protected $constraints;

    public function __construct($name, $route, array $defaults = [], array $constraints = [])
    {
        parent::__construct($name);

        $this->route = (string) $route;
        if ($this->route === '') {
            throw new \InvalidArgumentException('$route cannot be empty');
        }

        $this->defaults = $defaults;
        $this->constraints = $constraints;
    }
}
