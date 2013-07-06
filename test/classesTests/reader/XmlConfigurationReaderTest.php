<?php
include_once('PHPUnit/Autoload.php');
include_once(dirname(__FILE__)  . '/../../../src/ioc/readers/XmlConfigurationReader.php');
/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * XmlConfigurationReaderTest
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */


class XmlConfigurationReaderTest extends PHPUnit_Framework_TestCase {
     private $reader;

     /**
     * Set Up reader for each test
     *
     *
     */
    protected function setUp(){
        $callerDirectory = dirname(__FILE__) . '/../../';
        $configuration = $callerDirectory . '/helpers/configuration.xml';
        $this->reader = new XmlConfigurationReader($configuration);
    }
    
    /**
	 * Test the object returning
	 *
	 *
	 */
    public function testGetObject(){
        $this->assertNotNull($this->reader->getObject('itemDao'));     
        $this->assertEquals('null', $this->reader->getObject('unknown'));
    }
    
}