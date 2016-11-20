<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SK\ObjectToString\Exception;

/**
 * Class XmlErrorException.
 *
 * @author Sebastian Kroczek <sk@xbug.de>
 */
class XmlErrorException extends RuntimeException
{
    private $xmlError;

    public function __construct(\LibXMLError $error)
    {
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $level = 'WARNING';
                break;
            case LIBXML_ERR_FATAL:
                $level = 'FATAL';
                break;
            case LIBXML_ERR_ERROR:
                $level = 'ERROR';
                break;
            default:
                $level = 'UNKNOWN';
        }
        parent::__construct(
            sprintf(
                '[%s] %s in %s (line: %d, column: %d)',
                $level,
                $error->message,
                $error->file,
                $error->line,
                $error->column
            )
        );
        $this->xmlError = $error;
    }

    public function getXmlError()
    {
        return $this->xmlError;
    }
}
