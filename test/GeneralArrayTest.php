<?php
include_once('../src/ioc/context/Context_Factory.php');
include_once('../src/logger/Logger.php');
include_once('PHPUnit/Autoload.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * GeneralTest with this class the container is started with all capabilities.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class GeneralArrayTest extends PHPUnit_Framework_TestCase  {
    protected $context;
    
    
    private function arrayConfiguration(){
        return 
        array(
            array(
                'id' => 'connection',
                'class' => 'DBConnection',
                'resource' => 'no',
                'pear' => 'false',
            	'constructor-arguments' => array(
                    array('host' => '127.0.0.1'),
                    array('user' => 'root'),
                    array('password' => '1234'),
                    array('database' => 'dbtest')
                ),
            	'properties' => array()
    		),
    		array(
                'id' => 'personDAO',
                'class' => 'PersonDao',
                'resource' => 'no',
                'pear' => 'false',
            	'constructor-arguments' => array(),
            	'properties' => array(
     				array('connectionDb:ref' => 'connection'),
      				array('name' => 'Somebody')
    		    )
    		),
    		array(
                'id' => 'itemDao',
                'class' => 'ItemDao',
                'resource' => 'no',
                'pear' => 'false',
            	'constructor-arguments' => array(
    		        array('<ref>' => 'connection')
    		    ),
            	'properties' => array()
    		)
		);
    }
    
    
    /**
     * Set Up the context for each test
     *
     *
     */
    protected function setUp()
    {
        Logger::debug('>>>>>>>>>>>>>>>>>>>>> [ TEST ]');
        $basedir = dirname(__FILE__) . '/helpers/';
        $configuration = $this->arrayConfiguration();
        $this->context = Context_Factory::getArrayContext('testcase', $configuration, $basedir, true);
    }

    /**
     * Test simple object initialization
     *
     *
     */
    function testSimpleObjectInitialization(){
        $connection = $this->context->getObject('connection');
        $this->assertNotNull($connection->getConnectionString());
    }

    /**
     * Test constructor dependency injection
     *
     *
     */
    function testConstructorDependency(){
       $itemDao = $this->context->getObject('itemDao');
        $this->assertNotNull($itemDao->getItems());
    }

    /**
     * Test setter/getter dependency injection with simple values
     *
     *
     */
    function testGetterSetterDependency(){
        $personDao = $this->context->getObject('personDAO');
        $this->assertEquals('Somebody', $personDao->getName());
    }

    /**
     * Test setter/getter dependency injection with an object referenced
     *
     *
     */
    function testGetterSetterDependencyReferenced(){
       $personDao = $this->context->getObject('personDAO');
        $this->assertNotNull($personDao->getConnection());
    }
}

?>