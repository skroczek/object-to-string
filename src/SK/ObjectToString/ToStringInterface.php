<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString;

/**
 * Interface ToStringInterface.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
interface ToStringInterface
{
    /**
     * Generates a string for an object.
     *
     * @param string $name
     * @param object $object
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function generate($name, $object);
}
