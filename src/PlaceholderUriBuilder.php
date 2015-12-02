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
class PlaceholderUriBuilder implements UriBuilderInterface
{
    private $path;
    private $defaults;

    public function __construct($path, array $defaults = [])
    {
        $this->path = (string) $path;
        if ($this->path === '') {
            throw new \InvalidArgumentException('$path cannot be empty');
        }

        $this->defaults = $defaults;
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        $placeholders = [];

        foreach ($routeData->getParams() as $name => $value) {
            if (is_array($value)) {
                $value = implode('/', $value);
            }
            $placeholders[':'.$name] = $value;
        }

        $url = '';

        $explodedRoute = explode('(/', $this->path);
        $requiredRoutePart = array_shift($explodedRoute);

        if ($requiredRoutePart) {
            $url .= str_replace(array_keys($placeholders), array_values($placeholders), $requiredRoutePart);

            if (strpos($url, ':') !== false) {
                throw new \RuntimeException('Parameters are missing ('.$url.')');
            }

            if (!$explodedRoute) {
                return str_replace('+', '', $url);
            }
        }
        $optionalRouteParts = array_map(function ($value) { return rtrim($value, ')'); }, $explodedRoute);
        $optionalRouteParts = array_filter($optionalRouteParts, function ($v) { return $v !== ''; });


        $defaults = [];

        foreach ($this->defaults as $k => $v) {
            $defaults[':'.$k] = $v;
        }

        $tmpUrl = '';

        foreach ($optionalRouteParts as $routePart) {
            $urlPart = str_replace(array_keys($placeholders), array_values($placeholders), $routePart);
            $replaced = ($urlPart !== $routePart);

            if (strpos($urlPart, ':') !== false) {
                $urlPart = str_replace(array_keys($defaults), array_values($defaults), $urlPart);
            }

            if (strpos($urlPart, ':') !== false) {
                if ($replaced) {
                    throw new \RuntimeException('Parameters are missing ('.$urlPart.')');
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
