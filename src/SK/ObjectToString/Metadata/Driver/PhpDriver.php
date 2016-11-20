<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString\Metadata\Driver;

use Metadata\Driver\AbstractFileDriver;
use SK\ObjectToString\Exception\RuntimeException;
use SK\ObjectToString\Metadata\ClassMetadata;

/**
 * Class PhpDriver.
 *
 * @author  Sebastian Kroczek <sk@xbug.de>
 */
class PhpDriver extends AbstractFileDriver
{
    public function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $metadata = require $file;
        if (!$metadata instanceof ClassMetadata) {
            throw new RuntimeException(
                sprintf(
                    'The file %s was expected to return an instance of ClassMetadata, but returned %s.',
                    $file,
                    json_encode($metadata)
                )
            );
        }
        if ($metadata->name !== $class->name) {
            throw new RuntimeException(
                sprintf(
                    'The file %s was expected to return metadata for class %s, but instead returned metadata for class %s.',
                    $file,
                    $class->name,
                    $metadata->name
                )
            );
        }

        return $metadata;
    }

    protected function getExtension()
    {
        return 'php';
    }
}
