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
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlDriver.
 *
 * @author  Sebastian Kroczek <sk@xbug.de>
 */
class YamlDriver extends AbstractFileDriver
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
        $config = Yaml::parse(file_get_contents($file));

        if (!isset($config[$className = $class->name])) {
            throw new RuntimeException(sprintf('Expected metadata for class %s to be defined in %s.', $class->name, $file));
        }

        $config = $config[$className];
        $metadata = new ClassMetadata($className);
        $metadata->fileResources[] = $file;
        $metadata->fileResources[] = $class->getFileName();

        foreach ($config as $name => $value) {
            if (!array_key_exists('format', $value)) {
                throw new RuntimeException('Could not find key "format" inside yaml element.');
            }
            $metadata->addToString($name, $value['format'], array_key_exists('params', $value) ? $value['params'] : array());
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
        return 'yml';
    }
}
