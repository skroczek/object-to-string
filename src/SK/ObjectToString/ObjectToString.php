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

use Metadata\MetadataFactoryInterface;
use SK\ObjectToString\Metadata\ClassMetadata;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class ObjectToString.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class ObjectToString implements ToStringInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    public function __construct(MetadataFactoryInterface $metadataFactory, ExpressionLanguage $expressionLanguage)
    {
        $this->metadataFactory = $metadataFactory;
        $this->accessor = new PropertyAccessor();
        $this->expressionLanguage = $expressionLanguage;
    }

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
    public function generate($name, $object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException(sprintf('$object must be an object, but got "%s".', gettype($object)));
        }
        $cacheKey = get_class($object).'::'.$name;

        /** @var $metadata ClassMetadata */
        $metadata = $this->metadataFactory->getMetadataForClass(get_class($object));
        if (null === $metadata) {
            throw new \RuntimeException(
                sprintf('There were no object names defined for class "%s".', get_class($object))
            );
        }

        if (!isset($metadata->names[$name])) {
            throw new \RuntimeException(
                sprintf(
                    'The object of class "%s" has no name with type "%s". Available types: %s',
                    get_class($object),
                    $name,
                    implode(', ', array_keys($metadata->names))
                )
            );
        }

        $data = $metadata->names[$name];
        $params = array();
        foreach ($data['params'] as $key => $param) {
            $params[$key] = $this->accessor->getValue($object, $param);
        }
        $params['_this'] = $object;

//        var_dump(serialize($this->expressionLanguage->parse($name['format'], $params)->getNodes()));

        return $this->expressionLanguage->evaluate($data['format'], $params);
    }
}
