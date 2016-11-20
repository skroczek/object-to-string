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

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use SK\ObjectToString\Annotation\ObjectToString;
use SK\ObjectToString\Metadata\ClassMetadata;

/**
 * Class AnnotationDriver.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class AnnotationDriver implements DriverInterface
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $metadata = new ClassMetadata($class->name);

        $hasMetadata = false;
        foreach ($this->reader->getClassAnnotations($class) as $annot) {
            if ($annot instanceof ObjectToString) {
                $hasMetadata = true;
                $metadata->addToString($annot->name, $annot->format, $annot->params);
            }
        }

        return $hasMetadata ? $metadata : null;
    }
}
