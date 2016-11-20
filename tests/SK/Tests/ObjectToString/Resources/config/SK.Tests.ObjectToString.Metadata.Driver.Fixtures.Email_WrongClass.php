<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$metadata = new \SK\ObjectToString\Metadata\ClassMetadata(
    \SK\Tests\ObjectToString\Metadata\Driver\DummyClass1::class
);
$metadata->addToString('name', 'name', array('name' => 'name'));
$metadata->addToString('email', 'email', array('email' => 'email'));
$metadata->addToString('email_alternative', 'email', array('email' => 'emailAlternative'));
$metadata->addToString('full_email', 'name ~ \' <\' ~ email ~ \'>\'', array('email' => 'email', 'name' => 'name'));
$metadata->addToString(
    'full_email_alternative',
    'name ~ \' <\' ~ email ~ \'>\'',
    array('email' => 'emailAlternative', 'name' => 'name')
);

return $metadata;
