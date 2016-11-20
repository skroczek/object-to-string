<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString\Twig;

use SK\ObjectToString\ObjectToString;

/**
 * Class ToStringExtension.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class ToStringExtension extends \Twig_Extension
{
    private $objectToStringGenerator;

    public function __construct(ObjectToString $objectToStringGenerator)
    {
        $this->objectToStringGenerator = $objectToStringGenerator;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('object_to_string', array($this, 'toString')),
            new \Twig_SimpleFunction('__to_string', array($this, 'toString')),
        );
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('object_to_string', array($this, 'toStringFilter')),
            new \Twig_SimpleFilter('__to_string', array($this, 'toStringFilter')),
        );
    }

    public function toString($name, $object)
    {
        return $this->objectToStringGenerator->generate($name, $object);
    }

    public function toStringFilter($object, $name)
    {
        return $this->objectToStringGenerator->generate($name, $object);
    }

    public function getName()
    {
        return 'sk.object_to_string.extension';
    }
}
