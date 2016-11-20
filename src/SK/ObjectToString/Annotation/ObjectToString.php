<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString\Annotation;

/**
 * Object to string annotation.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 *
 * @Annotation
 * @Target("CLASS")
 */
class ObjectToString
{
    /** @var string @Required */
    public $name;

    /** @var string @Required */
    public $format;

    /** @var array */
    public $params = array();
}
