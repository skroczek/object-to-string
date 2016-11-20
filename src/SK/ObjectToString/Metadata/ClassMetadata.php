<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString\Metadata;

use Metadata\MergeableClassMetadata;

/**
 * Class ClassMetadata.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class ClassMetadata extends MergeableClassMetadata
{
    public $names = array();

    public function addToString($name, $format, array $params = array())
    {
        $this->names[$name] = array(
            'format' => $format,
            'params' => $params,
        );
    }

    public function serialize()
    {
        return serialize(
            array(
                $this->names,
                parent::serialize(),
            )
        );
    }

    public function unserialize($str)
    {
        list(
            $this->names,
            $parentStr
            ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}
