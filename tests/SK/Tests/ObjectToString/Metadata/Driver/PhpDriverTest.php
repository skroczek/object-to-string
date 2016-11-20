<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString\Metadata\Driver;

use Metadata\Driver\FileLocator;
use SK\ObjectToString\Metadata\Driver\PhpDriver;
use SK\Tests\ObjectToString\Metadata\Driver\Fixtures\Email;

class PhpDriverTest extends \PHPUnit_Framework_TestCase
{
    /** @var PhpDriver */
    private $driver;

    public function testLoad()
    {
        $metadata = $this->driver->loadMetadataForClass(new \ReflectionClass(Email::class));
        $this->assertCount(5, $metadata->names);
        $names = array(
            'name' => array(
                'format' => 'name',
                'params' => array(
                    'name' => 'name',
                ),
            ),
            'email' => array(
                'format' => 'email',
                'params' => array(
                    'email' => 'email',
                ),
            ),
            'email_alternative' => array(
                'format' => 'email',
                'params' => array(
                    'email' => 'emailAlternative',
                ),
            ),
            'full_email' => array(
                'format' => 'name ~ \' <\' ~ email ~ \'>\'',
                'params' => array(
                    'email' => 'email',
                    'name' => 'name',
                ),
            ),
            'full_email_alternative' => array(
                'format' => 'name ~ \' <\' ~ email ~ \'>\'',
                'params' => array(
                    'email' => 'emailAlternative',
                    'name' => 'name',
                ),
            ),
        );
        $this->assertSame($names, $metadata->names);
    }

    /**
     * @expectedException \SK\ObjectToString\Exception\RuntimeException
     */
    public function testLoadWrongMetadataClass()
    {
        $configFile = realpath(__DIR__.'/../../Resources/config/SK.Tests.ObjectToString.Metadata.Driver.Fixtures.Email_WrongMetadataClass.php');
        $this->driver->loadMetadataFromFile(new \ReflectionClass(Email::class), $configFile);
    }

    /**
     * @expectedException \SK\ObjectToString\Exception\RuntimeException
     */
    public function testLoadWrongClass()
    {
        $configFile = realpath(__DIR__.'/../../Resources/config/SK.Tests.ObjectToString.Metadata.Driver.Fixtures.Email_WrongClass.php');
        $this->driver->loadMetadataFromFile(new \ReflectionClass(Email::class), $configFile);
    }

    public function testLoadReturnsNullWhenNoAnnotation()
    {
        $this->assertNull($this->driver->loadMetadataForClass(new \ReflectionClass('stdClass')));
    }

    protected function setUp()
    {
        $this->driver = new PhpDriver(new FileLocator(array('' => realpath(__DIR__.'/../../Resources/config'))));
    }
}

class DummyClass1
{
}
class DummyClass2
{
}
