<?php

/*
 * This file is part of the Object To String  package.
 *
 * (c) Sebastian Kroczek <sk@xbug.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!is_file($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    echo 'Unable to find "vendor/autoload.php". Did you run "composer install"?'.PHP_EOL;
    exit(1);
}
require_once $autoloadFile;
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
spl_autoload_register(
    function ($class) {
        if (0 === strpos($class, 'SK\Tests\\')) {
            $path = __DIR__.'/../tests/'.strtr($class, '\\', '/').'.php';
            if (file_exists($path) && is_readable($path)) {
                require_once $path;

                return true;
            }
        }
    }
);
