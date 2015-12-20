<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
abstract class AbstractRoute implements RouteInterface
{
    protected $name;
    protected $path;
    protected $handler;
    protected $values = [];
    protected $allowedRequestMethods = [];

    public function __construct($path, callable $handler = null)
    {
        $this->path = (string) $path;
        if ($this->path === '') {
            throw new \InvalidArgumentException('$path cannot be empty');
        }

        $this->handler = $handler;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function handler(callable $handler)
    {
        $this->handler = $handler;

        return $this;
    }

    public function values(array $values)
    {
        $this->values = $values;

        return $this;
    }

    public function allows(array $allowedRequestMethods)
    {
        $this->allowedRequestMethods = $allowedRequestMethods;

        return $this;
    }

    public function __call($name, array $arguments)
    {
        if (!$arguments) {
            throw new \RuntimeException('Route parameter "'.$name.'" requires a value');
        }

        $this->values['_'.$name] = (count($arguments) === 1) ? $arguments[0] : $arguments;

        return $this;
    }

    public function isRequestMethodAllowed($method)
    {
        return in_array($method, $this->allowedRequestMethods);
    }
}
