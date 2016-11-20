<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\Tests\ObjectToString\Exception;

use SK\ObjectToString\Exception\XmlErrorException;

class XmlErrorExceptionTest extends \PHPUnit_Framework_TestCase
{
    private function createLibXMLError($errorLevel, $message = '', $file = '', $line = 0, $column = 0)
    {
        $libXmlError = new \LibXMLError();
        $libXmlError->level = $errorLevel;
        $libXmlError->message = $message;
        $libXmlError->file = $file;
        $libXmlError->line = $line;
        $libXmlError->column = $column;

        return $libXmlError;
    }

    public function testUnknownLevel()
    {
        $libXmlError = $this->createLibXMLError('');
        $exception = new XmlErrorException($libXmlError);

        $this->assertSame('[UNKNOWN]  in  (line: 0, column: 0)', $exception->getMessage());
        $this->assertSame($exception->getXmlError(), $libXmlError);
    }

    public function testErrorLevel()
    {
        $libXmlError = $this->createLibXMLError(LIBXML_ERR_ERROR);
        $exception = new XmlErrorException($libXmlError);

        $this->assertSame('[ERROR]  in  (line: 0, column: 0)', $exception->getMessage());
        $this->assertSame($exception->getXmlError(), $libXmlError);
    }

    public function testFatalLevel()
    {
        $libXmlError = $this->createLibXMLError(LIBXML_ERR_FATAL);
        $exception = new XmlErrorException($libXmlError);

        $this->assertSame('[FATAL]  in  (line: 0, column: 0)', $exception->getMessage());
        $this->assertSame($exception->getXmlError(), $libXmlError);
    }

    public function testFatalWarning()
    {
        $libXmlError = $this->createLibXMLError(LIBXML_ERR_WARNING);
        $exception = new XmlErrorException($libXmlError);

        $this->assertSame('[WARNING]  in  (line: 0, column: 0)', $exception->getMessage());
        $this->assertSame($exception->getXmlError(), $libXmlError);
    }
}
