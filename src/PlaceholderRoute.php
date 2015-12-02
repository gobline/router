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
class PlaceholderRoute extends AbstractPlaceholderRoute
{
    public function match(ServerRequestInterface $request)
    {
        $matcher = new PlaceholderRequestMatcher($this->path, $this->constraints);

        $params = $matcher->match($request);

        if ($params === false) {
            return false;
        }

        return new RouteData($this->name,  array_merge($params + $this->defaults, $this->values), $this->handler);
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        $urlMaker = new PlaceholderUriBuilder($this->path, $this->defaults);

        return $urlMaker->buildUri($routeData, $language);
    }

    public function i18n(array $translations)
    {
        return (new I18n\PlaceholderRoute($this->name, $this->path, $this->handler, $translations))
            ->values($this->values)
            ->defaults($this->defaults)
            ->constraints($this->constraints);
    }
}
