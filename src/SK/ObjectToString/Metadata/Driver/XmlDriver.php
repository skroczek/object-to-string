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
use SK\ObjectToString\Exception\XmlErrorException;
use SK\ObjectToString\Metadata\ClassMetadata;

/**
 * Class XmlDriver.
 *
 * @author  Sebastian Kroczek <sk@xbug.de>
 */
class XmlDriver extends AbstractFileDriver
{
    /**
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param \ReflectionClass $class
     * @param string           $file
     *
     * @return \Metadata\ClassMetadata|null
     */
    public function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $previous = libxml_use_internal_errors(true);
        $elem = simplexml_load_file($file);
        libxml_use_internal_errors($previous);
        if (false === $elem) {
            throw new XmlErrorException(libxml_get_last_error());
        }
        $metadata = new ClassMetadata($name = $class->name);
        if (!$elems = $elem->xpath("./class[@name = '".$name."']")) {
            throw new RuntimeException(sprintf('Could not find class %s inside XML element.', $name));
        }
        $elem = reset($elems);
        $metadata->fileResources[] = $file;
        $metadata->fileResources[] = $class->getFileName();
        foreach ($elem->xpath('./name') as $r) {
            if ('' === $name = (string) $r->attributes()->{'name'}) {
                throw new RuntimeException('Could not find attribute "type" inside XML element.');
            }
            if ('' === $format = (string) $r->attributes()->{'format'}) {
                throw new RuntimeException('Could not find attribute "format" inside XML element.');
            }
            $params = array();
            foreach ($r->xpath('./param') as $p) {
                $params[(string) $p->attributes()] = (string) $p;
            }
            $metadata->addToString($name, $format, $params);
        }

        return $metadata;
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    protected function getExtension()
    {
        return 'xml';
    }
}
