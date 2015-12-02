<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Router\Rule;

use Psr\Http\Message\ServerRequestInterface;
use Gobline\Router\RouteInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RuleCollection
{
    private $rules;

    public function __construct()
    {
        $this->rules = [
            new Method(),
        ];
    }

    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    public function isRequestMatchingRouteRules(ServerRequestInterface $request, RouteInterface $route)
    {
        foreach ($this->rules as $rule) {
            if (!$rule->match($request, $route)) {
                return false;
            }
        }

        return true;
    }
}
