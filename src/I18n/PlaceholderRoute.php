<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router\I18n;

use Psr\Http\Message\ServerRequestInterface;
use Gobline\Router\AbstractPlaceholderRoute;
use Gobline\Router\PlaceholderRequestMatcher;
use Gobline\Router\PlaceholderUriBuilder;
use Gobline\Router\RouteData;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class PlaceholderRoute extends AbstractPlaceholderRoute
{
    protected $translations;

    public function __construct($name, $path, callable $handler = null, array $translations)
    {
        parent::__construct($name, $path, $handler);

        $this->translations = new RouteTranslations($translations);
    }

    public function match(ServerRequestInterface $request)
    {
        $language = $request->getAttribute('_language', null);

        $path = $this->translations->getTranslation($language) ?: $this->path;

        $matcher = new PlaceholderRequestMatcher($path, $this->constraints);

        $params = $matcher->match($request);

        if ($params === false) {
            return false;
        }

        if ($language) {
            foreach ($params as $name => &$value) {
                if (is_array($value)) {
                    foreach ($value as &$v) {
                        $v = $this->translations->translatePlaceholderValue($name, $v, $language, true) ?: $v;
                    }
                } else {
                    $value = $this->translations->translatePlaceholderValue($name, $value, $language, true) ?: $value;
                }
            }
        }

        return new RouteData($this->name, array_merge($params + $this->defaults, $this->values), $this->handler);
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        $path = $this->translations->getTranslation($language) ?: $this->path;

        $params = $routeData->getParams();

        if ($language) {
            foreach ($params as $name => &$value) {
                if (is_array($value)) {
                    foreach ($value as &$v) {
                        $v = $this->translations->translatePlaceholderValue($name, $v, $language) ?: $v;
                    }
                } else {
                    $value = $this->translations->translatePlaceholderValue($name, $value, $language) ?: $value;
                }
            }
        }

        $uriBuilder = new PlaceholderUriBuilder($path, $this->defaults);

        return $uriBuilder->buildUri(new RouteData($routeData->getName(), $params), $language);
    }
}
