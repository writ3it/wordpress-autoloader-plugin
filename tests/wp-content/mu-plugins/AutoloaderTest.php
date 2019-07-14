<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once MU_PLUGINS.'/!!!autoloader.php';
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 14.07.19
 * Time: 16:30
 */

class AutoloaderTest extends TestCase
{
    /**
     * Autoloader must be loaded as fast as possible.
     * Sort function is a bottleneck in wordpress in this case.
     */
    public function test_sort_function(){
        $autoloaderName = '!!!autoloader.php';
        $data = [
            '123.php',
            '.example.php',
            'example.php',
            'autoexample.php',
            $autoloaderName
        ];
        sort($data);
        $first = array_values($data)[0];
        $this->assertEquals($autoloaderName,$first);
    }

}