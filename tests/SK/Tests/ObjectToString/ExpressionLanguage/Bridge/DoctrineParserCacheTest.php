<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString\ExpressionLanguage\Bridge;

use Doctrine\Common\Cache\ArrayCache;
use SK\ObjectToString\ExpressionLanguage\Bridge\DoctrineParserCache;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class DoctrineParserCacheTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Cache\Cache')) {
            $this->markTestSkipped('Doctrine\Common is not installed.');
        }
    }

    public function testLoadEvictPutClassMetadataFromInCache()
    {
        $cache = new DoctrineParserCache(new ArrayCache());

        $language = new ExpressionLanguage();

        $this->assertNull($cache->fetch('foo'));
        $cache->save('foo', $parsedExpression = $language->parse('1 + 4', array()));

        $this->assertSame($parsedExpression, $cache->fetch('foo'));

        $cache->delete('foo');
        $this->assertNull($cache->fetch('foo'));
    }
}
