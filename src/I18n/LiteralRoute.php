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
use Gobline\Router\AbstractRoute;
use Gobline\Router\RouteData;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class LiteralRoute extends AbstractRoute
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

        // if language is null, don t translate

        $path = $this->translations->getTranslation($language) ?: $this->path;

        if ($request->getUri()->getPath() !== $path) {
            return false;
        }

        return new RouteData($this->name, $this->values, $this->handler);
    }

    public function buildUri(RouteData $routeData, $language = null)
    {
        return $this->translations->getTranslation($language) ?: $this->path;
    }
}
