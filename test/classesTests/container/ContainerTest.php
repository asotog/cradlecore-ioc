<?php
include_once('PHPUnit/Autoload.php');
include_once(dirname(__FILE__)  . '/../../../src/ioc/container/Container.php');
/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ContainerTest
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */


class ContainerTest extends PHPUnit_Framework_TestCase {
    private $container;
    
    /**
     * Set Up the container for each test
     *
     *
     */
    protected function setUp()
    {
        $this->container = new Container();
    }

     /**
     * Add to container
     *
     *
     */
    public function testAddObject(){
        $this->container->addObject('test', new Foo());
        $this->assertNotEquals('null',$this->container->getObject('test'));
        $this->assertEquals('null',$this->container->getObject('unknown'));
    }
}

class Foo {
    public $property;

    public function fooFunction() {
        echo 'testing...';
    }
}