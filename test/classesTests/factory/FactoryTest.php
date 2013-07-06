<?php
include_once('PHPUnit/Autoload.php');
include_once(dirname(__FILE__)  . '/../../../src/ioc/factory/Factory.php');
include_once(dirname(__FILE__)  . '/../../../src/ioc/container/Container.php');
/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * FactoryTest
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */


class FactoryTest extends PHPUnit_Framework_TestCase {
    private  $factory;

    /**
     * Set Up the factory for each test
     *
     *
     */
    protected function setUp()
    {
        $this->factory = new Factory();
        $this->factory->instanceContainer(new Container());
    }

     /**
     * Add to container
     *
     *
     */
    public function testAddToContainer(){
        $object = 
        array(
        'id' => 'connection',
        'class' => 'DBConnection',
        'resource' => 'no',
        'pear' => 'false',
        'constructor-arguments' =>
        array(
            array(
                'host' => '127.0.0.1'),
            array(
                'user'=> 'root' ),
            array(
        		'password'=> '1234'),
            array(
        		'database'=>'dbtest' )
             ),
        'properties'=>  array()
        );
        
        $this->factory->addObjectToContainer($object);
        $this->assertNotNull($this->factory->getObject('connection'));
    }
}