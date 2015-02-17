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

use Mendo\Http\Request\HttpRequestInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class PlaceholderRequestMatcher implements RequestMatcherInterface
{
    private $route;
    private $constraints;
    private $paramNames;
    private $paramNamesPath;

    public function __construct($route, array $constraints = [])
    {
        $this->route = (string) $route;
        if ($this->route === '') {
            throw new \InvalidArgumentException('$route cannot be empty');
        }

        $this->constraints = $constraints;
    }

    // https://github.com/codeguy/Slim/blob/master/Slim/Route.php
    public function match(HttpRequestInterface $httpRequest)
    {
        //Convert URL params into regex patterns, construct a regex for this route, init params
        $patternAsRegex = preg_replace_callback(
            '#:([\w]+)\+?#',
            array($this, 'matchesCallback'),
            str_replace(')', ')?', (string)$this->route)
        );

        if (substr($this->route, -1) === '/') {
            $patternAsRegex .= '?';
        }

        $regex = '#^' . $patternAsRegex . '$#';
        //Cache URL params' names and values if this route matches the current HTTP request
        if (!preg_match($regex, $httpRequest->getPath(), $paramValues)) {
            return false;
        }

        $params = [];

        foreach ($this->paramNames as $name) {
            if (isset($paramValues[$name])) {
                if (isset($this->paramNamesPath[$name])) {
                    $params[$name] = explode('/', urldecode($paramValues[$name]));
                } else {
                    $params[$name] = urldecode($paramValues[$name]);
                }
            }
        }

        return $params;
    }

    // https://github.com/codeguy/Slim/blob/master/Slim/Route.php
    private function matchesCallback($m)
    {
        $this->paramNames[] = $m[1];
        if (isset($this->constraints[$m[1]])) {
            return '(?P<' . $m[1] . '>' . $this->constraints[$m[1]] . ')';
        }
        if (substr($m[0], -1) === '+') {
            $this->paramNamesPath[$m[1]] = 1;
            return '(?P<' . $m[1] . '>.+)';
        }
        return '(?P<' . $m[1] . '>[^/]+)';
    }
}
