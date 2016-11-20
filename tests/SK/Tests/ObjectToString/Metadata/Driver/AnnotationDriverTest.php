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

use Doctrine\Common\Annotations\AnnotationReader;
use SK\ObjectToString\Metadata\Driver\AnnotationDriver;
use SK\Tests\ObjectToString\Metadata\Driver\Fixtures\Email;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
    /** @var AnnotationDriver */
    private $driver;

    public function testLoad()
    {
        $metadata = $this->driver->loadMetadataForClass(new \ReflectionClass(Email::class));
        $this->assertCount(6, $metadata->names);
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
            'full_email_validated' => array(
                'format' => '_this.getName() ~ (_this.isValidated() ? \' (validated)\') ~\' <\' ~ _this.getEmail() ~ \'>\'',
                'params' => array(
                ),
            ),
        );
        $this->assertSame($names, $metadata->names);
    }

    public function testLoadReturnsNullWhenNoAnnotation()
    {
        $this->assertNull($this->driver->loadMetadataForClass(new \ReflectionClass('stdClass')));
    }

    protected function setUp()
    {
        $this->driver = new AnnotationDriver(new AnnotationReader());
    }
}
