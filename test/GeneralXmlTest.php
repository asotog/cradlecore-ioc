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

class GeneralXmlTest extends PHPUnit_Framework_TestCase  {
    protected $context;
    protected $context2;
    protected $context3;

    /**
     * Set Up the context for each test
     *
     *
     */
    protected function setUp()
    {
        Logger::debug('>>>>>>>>>>>>>>>>>>>>> [ TEST ]');
        $callerDirectory = dirname(__FILE__);
        $configuration = $callerDirectory . '/helpers/configuration.xml';
        $configuration2 = $callerDirectory . '/helpers/oneObjectConfiguration.xml';
        $configuration3 = $callerDirectory . '/helpers/configuration2.xml';
        $this->context = Context_Factory::getXmlContext($configuration, true);
        $this->context2 = Context_Factory::getXmlContext($configuration2, true);
        $this->context3 = Context_Factory::getXmlContext($configuration3, true);
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

    /**
     * Test for more complex object
     *
     *
     */
    function testForMoreComplexObject(){
        $myService = $this->context->getObject('myService');
        $this->assertNotNull($myService->getItemDao()->getItems());
    }
    
    /**
     * Test one object configuration
     *
     *
     */
    function testOneObjectXmlConfiguration(){
        $connection = $this->context2->getObject('connection');
        $this->assertNotNull($connection->getConnectionString());
    }
    
    /**
     * 
     * 
     */
    function testResourceAttributteOnObject() {
        $validator = $this->context3->getObject('validator');
        $this->assertNotNull($validator->isEmpty('hello'));
    }
}

?>