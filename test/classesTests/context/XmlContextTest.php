<?php
include_once('PHPUnit/Autoload.php');
include_once(dirname(__FILE__)  . '/../../../src/ioc/context/XmlContext.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * XmlContextTest
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */



class XmlContextTest extends PHPUnit_Framework_TestCase  {
    protected $context;

    /**
     * Set Up the context for each test
     *
     *
     */
    protected function setUp()
    {
        $callerDirectory = dirname(__FILE__) . '/../../';
        $configuration = $callerDirectory . '/helpers/configuration.xml';
        $this->context = new XmlContext($configuration, true);
    }

    /**
     * Test getObject function
     *
     *
     */
    function testGetObject(){
        $itemDao = $this->context->getObject('itemDao');
        $this->assertNotNull($itemDao);
        
        $unknown = $this->context->getObject('unknown');
        $this->assertNull($unknown);
    }
}

?>