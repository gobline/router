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
abstract class AbstractPlaceholderRoute extends AbstractRoute
{
    protected $defaults = [];
    protected $constraints = [];

    public function defaults(array $defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    public function constraints(array $constraints)
    {
        $this->constraints = $constraints;

        return $this;
    }
}
