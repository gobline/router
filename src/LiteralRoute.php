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
class LiteralRoute extends AbstractRoute
{
    public function match(ServerRequestInterface $request)
    {
        if ($request->getUri()->getPath() !== $this->path) {
            return false;
        }

        return new RouteData($this->name, $this->values, $this->handler);
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        return $this->path;
    }

    public function i18n(array $translations)
    {
        return (new I18n\LiteralRoute($this->path, $this->handler, $translations))
            ->setName($this->name)
            ->values($this->values)
            ->allows($this->allowedRequestMethods);
    }
}
