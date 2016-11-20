<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString\Metadata;

use SK\ObjectToString\Metadata\ClassMetadata;
use SK\Tests\ObjectToString\Metadata\Driver\Fixtures\Email;

class ClassMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $metadata = new ClassMetadata(Email::class);
        $metadata->addToString('foo', 'bar', array('foo' => 'bar'));
        $serialized = $metadata->serialize();
        $metadata->unserialize($serialized);

        $metadata2 = new ClassMetadata(Email::class);

        $this->assertNotSame(
            $serialized,
            $metadata2->serialize()
        );

        $metadata2->unserialize($serialized);

        $this->assertSame(
            $serialized,
            $metadata2->serialize()
        );
    }
}
