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

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class PlaceholderRequestMatcher implements RequestMatcherInterface
{
    private $path;
    private $constraints;
    private $paramNames;
    private $paramNamesPath;

    public function __construct($path, array $constraints = [])
    {
        $this->path = (string) $path;
        if ($this->path === '') {
            throw new \InvalidArgumentException('$path cannot be empty');
        }

        $this->constraints = $constraints;
    }

    public function match(ServerRequestInterface $request)
    {
        //Convert URL params into regex patterns, construct a regex for this route, init params
        $patternAsRegex = preg_replace_callback(
            '#:([\w]+)\+?#',
            array($this, 'matchesCallback'),
            str_replace(')', ')?', (string)$this->path)
        );

        if (substr($this->path, -1) === '/') {
            $patternAsRegex .= '?';
        }

        $regex = '#^' . $patternAsRegex . '$#';
        //Cache URL params' names and values if this route matches the current HTTP request
        if (!preg_match($regex, $request->getUri()->getPath(), $paramValues)) {
            return false;
        }

        $params = [];

        if ($this->paramNames) {
            foreach ($this->paramNames as $name) {
                if (isset($paramValues[$name])) {
                    if (isset($this->paramNamesPath[$name])) {
                        $params[$name] = explode('/', urldecode($paramValues[$name]));
                    } else {
                        $params[$name] = urldecode($paramValues[$name]);
                    }
                }
            }
        }

        return $params;
    }

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
