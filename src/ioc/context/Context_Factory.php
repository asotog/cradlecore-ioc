<?php
include_once(dirname(__FILE__)  . '/XmlContext.php');
include_once(dirname(__FILE__)  . '/ArrayContext.php');

/**
 * Factory class to instantiate all context types, is more like an utility class
 */
class Context_Factory {

    /**
     * Constructor.
     * @return void
     */
    private function __construct(){

    }


    /**
     * Instantiates a new XmlContext.
     * 
     * @param string $configuration Absolute xml configuration file location.
     * @param boolean $debug Optional, default value is false, generates a log file relative to configuration file location.
     * @return XmlContext
     */
    public static function getXmlContext($configuration, $debug = false){
        return new XmlContext($configuration, $debug);
    }

     /**
     * Instantiates a new ArrayContext.
     * 
     * @return ArrayContext
     */
    public static function getArrayContext($id, $configuration, $basedir, $debug = false){
        //TODO : implements the array based configuration
        return new ArrayContext($id, $basedir, $configuration, $debug);
    }
}