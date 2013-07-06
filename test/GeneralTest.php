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

class GeneralTest extends PHPUnit_Framework_TestCase  {
    protected $context;

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
        $this->context = Context_Factory::getXmlContext($configuration, true);
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