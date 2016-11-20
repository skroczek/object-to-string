<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString\Twig;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverInterface;
use Metadata\MetadataFactory;
use SK\ObjectToString\Metadata\Driver\AnnotationDriver;
use SK\ObjectToString\ObjectToString;
use SK\ObjectToString\Twig\ToStringExtension;
use SK\Tests\ObjectToString\Metadata\Driver\Fixtures\Email;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ToStringExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ToStringExtension
     */
    private $extension;

    public function testGetFunctions()
    {
        $functions = $this->extension->getFunctions();
        $this->assertSame(2, count($functions));

        $expectedFunctionNames = array('__to_string', 'object_to_string');

        foreach ($functions as $f) {
            $this->assertInstanceOf(\Twig_SimpleFunction::class, $f);
        }
    }

    public function testGetFilter()
    {
        $filters = $this->extension->getFilters();
        $this->assertSame(2, count($filters));

        $expectedFunctionNames = array('__to_string', 'object_to_string');

        foreach ($filters as $f) {
            $this->assertInstanceOf(\Twig_SimpleFilter::class, $f);
        }
    }

    public function testGetName()
    {
        $this->assertSame('sk.object_to_string.extension', $this->extension->getName());
    }

    public function testToString()
    {
        $email = new Email('John Doe', 'john.doe@example.com', 'jd@example.com');
        $this->assertSame('John Doe', $this->extension->toString('name', $email));
    }

    public function testToStringFilter()
    {
        $email = new Email('John Doe', 'john.doe@example.com', 'jd@example.com');
        $this->assertSame('John Doe', $this->extension->toStringFilter($email, 'name'));
    }

    protected function setUp()
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $extension = new ToStringExtension($this->createObjectToString($driver));

        $this->extension = $extension;
    }

    private function createObjectToString(DriverInterface $driver)
    {
        $metadataFactory = new MetadataFactory($driver);
        $expressionLanguage = new ExpressionLanguage();

        $objectToString = new ObjectToString($metadataFactory, $expressionLanguage);

        return $objectToString;
    }
}
