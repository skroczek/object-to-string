<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverInterface;
use Metadata\MetadataFactory;
use SK\ObjectToString\Metadata\Driver\AnnotationDriver;
use SK\ObjectToString\ObjectToString;
use SK\Tests\ObjectToString\Metadata\Driver\Fixtures\Email;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ObjectToStringTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotation()
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $ots = $this->createObjectToString($driver);
        $email = new Email('John Doe', 'john.doe@example.com', 'jd@example.com');

        $this->assertSame('John Doe', $ots->generate('name', $email));
        $this->assertSame('john.doe@example.com', $ots->generate('email', $email));
        $this->assertSame('jd@example.com', $ots->generate('email_alternative', $email));
        $this->assertSame('John Doe <john.doe@example.com>', $ots->generate('full_email', $email));
        $this->assertSame('John Doe <jd@example.com>', $ots->generate('full_email_alternative', $email));
        $this->assertSame('John Doe <john.doe@example.com>', $ots->generate('full_email_validated', $email));
        $email->setValidated(true);
        $this->assertSame(
            'John Doe (validated) <john.doe@example.com>',
            $ots->generate('full_email_validated', $email)
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testWrongExpressionReturnType()
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $metadataFactory = new MetadataFactory($driver);
        $expressionLanguage = $this->getMockBuilder('Symfony\Component\ExpressionLanguage\ExpressionLanguage')->getMock();
        $expressionLanguage->method('evaluate')->willReturn(array());

        $ots = new ObjectToString($metadataFactory, $expressionLanguage);
        $email = new Email('John Doe', 'john.doe@example.com', 'jd@example.com');
        $ots->generate('name', $email);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithNonObject()
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $ots = $this->createObjectToString($driver);
        $ots->generate('full_email_alternative', '');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testObjectWithoutMetadata()
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $ots = $this->createObjectToString($driver);
        $ots->generate('full_email_alternative', new \stdClass());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnknownType()
    {
        $driver = new AnnotationDriver(new AnnotationReader());
        $ots = $this->createObjectToString($driver);
        $email = new Email();
        $ots->generate('foobar', $email);
    }

    private function createObjectToString(DriverInterface $driver)
    {
        $metadataFactory = new MetadataFactory($driver);
        $expressionLanguage = new ExpressionLanguage();

        $objectToString = new ObjectToString($metadataFactory, $expressionLanguage);

        return $objectToString;
    }
}
