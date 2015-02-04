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
abstract class AbstractRouter implements RouterInterface
{
    protected $name;

    public function __construct($name)
    {
        $this->name = (string) $name;
        if ($this->name === '') {
            throw new \InvalidArgumentException('$name cannot be empty');
        }

        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function makeKeyValuePairs(array $array)
    {
        $pairs = [];
        $nb = count($array);
        $i = 0;
        for (; $i < $nb - 1; $i += 2) {
            $pairs[$array[$i]] = $array[$i+1];
        }
        if ($i < $nb) {
            $pairs[$array[$i]] = '';
        }

        return $pairs;
    }

    protected function encodeParam($param)
    {
        return rawurlencode(str_replace('/', '%%', (string) $param));
    }

    protected function decodeParam($param)
    {
        return str_replace('%%', '/', rawurldecode($param));
    }

    protected function getSegments($path)
    {
        $path = trim($path, '/');

        if ($path === '') {
            return [];
        }

        return explode('/', $path);
    }
}
