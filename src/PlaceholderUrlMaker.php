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
class PlaceholderUrlMaker implements UrlMakerInterface
{
    private $route;
    private $defaults;

    public function __construct($route, array $defaults = [])
    {
        $this->route = (string) $route;
        if ($this->route === '') {
            throw new \InvalidArgumentException('$route cannot be empty');
        }

        $this->defaults = $defaults;
    }

    public function makeUrl(RouteData $routeData, $language = null)
    {
        $placeholders = [];

        foreach ($routeData->getParams() as $name => $value) {
            if (is_array($value)) {
                $value = implode('/', $value);
            }
            $placeholders[':'.$name] = $value;
        }

        $url = '';

        $route = explode('(/', $this->route);
        $requiredRoutePart = array_shift($route);

        if ($requiredRoutePart) {
            $url .= str_replace(array_keys($placeholders), array_values($placeholders), $requiredRoutePart);

            if (strpos($url, ':') !== false) {
                throw new \RuntimeException('Parameters are missing');
            }

            if (!$route) {
                return str_replace('+', '', $url);
            }
        }

        $optionalRouteParts = array_map(function ($value) { return rtrim($value, ')'); }, $route);

        $defaults = [];

        foreach ($this->defaults as $k => $v) {
            $defaults[':'.$k] = $v;
        }

        $tmpUrl = '';

        foreach ($optionalRouteParts as $routePart) {
            if (empty($routePart)) {
                continue;
            }

            $urlPart = str_replace(array_keys($placeholders), array_values($placeholders), $routePart);
            $replaced = ($urlPart !== $routePart);

            if (strpos($urlPart, ':') !== false) {
                $urlPart = str_replace(array_keys($defaults), array_values($defaults), $urlPart);
            }

            if (strpos($urlPart, ':') !== false) {
                if ($replaced) {
                    throw new \RuntimeException('Parameters are missing');
                }
                break;
            }

            $tmpUrl .= '/'.$urlPart;

            if ($urlPart !== str_replace(array_keys($defaults), array_values($defaults), $routePart)) {
                $url .= $tmpUrl;
                $tmpUrl = '';
            }
        }

        return str_replace('+', '', $url);
    }
}
