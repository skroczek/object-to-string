<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString\ExpressionLanguage\Bridge;

use Doctrine\Common\Cache\Cache;
use Symfony\Component\ExpressionLanguage\ParsedExpression;
use Symfony\Component\ExpressionLanguage\ParserCache\ParserCacheInterface;

/**
 * Class DoctrineParserCache.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class DoctrineParserCache implements ParserCacheInterface
{
    /**
     * @var Cache
     */
    private $doctrineCache;

    public function __construct(Cache $doctrineCache)
    {
        $this->doctrineCache = $doctrineCache;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        $value = $this->doctrineCache->fetch($key);
        if (false !== $value) {
            return $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, ParsedExpression $expression)
    {
        $this->doctrineCache->save($key, $expression);
    }

    /**
     * Deletes an expression from the cache.
     *
     * @param $key
     */
    public function delete($key)
    {
        $this->doctrineCache->delete($key);
    }
}
