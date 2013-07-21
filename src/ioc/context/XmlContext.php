<?php
include_once(dirname(__FILE__)  . '/Context.php');
include_once(dirname(__FILE__)  . '/../readers/XmlConfigurationReader.php');
include_once(dirname(__FILE__)  . '/../../exception/CradleCoreException.php');
include_once(dirname(__FILE__)  . '/../../logger/Logger.php');
include_once(dirname(__FILE__)  . '/../../utils/Constants.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * XmlContext used to instance the container from the application.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */
class XmlContext implements Context {
    private $xmlConfigurationReader;
    private $configurationFile;

    /**
     *  Constructor
     *
     * @param string $configuration Absolute xml configuration file location.
     * @param boolean $debug Optional, default value is false, generates a log file relative to configuration file location.
     * @return void
     *
     */
    public function __construct($configuration, $debug = false){
        $this->registerCurrentRequestGlobals($configuration, $debug);
        $this->configurationFile = $configuration;
        $this->initialize();
    }

    /**
     *  Registers variables as execution environment variables
     * 
     * @param string $configuration Absolute xml configuration file location.
     * @param boolean $debug Optional, default value is false, generates a log file relative to configuration file location.
     * @return void
     *
     */
    private function registerCurrentRequestGlobals($configuration, $debug){
        putenv('MEMORYID=' . substr(base64_encode($configuration), 1, 10));
        putenv('CALLERDIR=' . $this->getCallerDirectory($configuration));
        putenv('DEBUG=' . 'false');
        if($debug){
            putenv('DEBUG=' . 'true');}
    }

    /**
     * Get the path from the xml configuration file path
     *
     * @param string $configuration Absolute xml configuration file location.
     * @return string
     */
    private function getCallerDirectory($configuration){
        $path = pathinfo($configuration);
        return $path['dirname'] . Constants::$DIR_SEPARATOR;
    }

    /**
     * Initialize the xml reader object and starts to iterate over the file(s)
     * 
     * @return void
     */
    public function initialize(){
        $this->xmlConfigurationReader = new XmlConfigurationReader($this->configurationFile);
    }

    /**
     * Obtains the instanced object
     *
     * @param string $key Id to retrieve the object
     * @return Object
     */
    public function getObject($key) {
        $object = $this->xmlConfigurationReader->getObjectFromContainer($key);
        if($object!= 'null'){
            Logger::debug(' >> Object [' . $key . '] retrieved');
            return $object;
        } else {
            new CradleCoreException('Object [' . $key . '] does not exists');
            Logger::debug(' >> [ERROR] Object [' . $key . '] does not exists');
            return null;
        }
    }
}
?>