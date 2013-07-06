<?php
include_once(dirname(__FILE__)  . '/Context.php');
include_once(dirname(__FILE__)  . '/../../logger/Logger.php');
include_once(dirname(__FILE__)  . '/../readers/ArrayConfigurationReader.php');

class ArrayContext implements Context {
    private $arrayConfigurationReader;
    private $configuration;

    public function __construct($id, $basedir, $configuration, $debug = false){
        $this->registerCurrentRequestGlobals($id, $basedir, $debug);
        $this->configuration = $configuration;
        $this->initialize();
    }

    public function initialize(){
        $this->arrayConfigurationReader = new ArrayConfigurationReader($this->configuration);
    }

    private function registerCurrentRequestGlobals($id, $basedir, $debug){
        putenv('MEMORYID=' . substr(base64_encode($id), 1, 10));
        putenv('CALLERDIR=' . $basedir);
        putenv('DEBUG=' . 'false');
        if($debug){
            putenv('DEBUG=' . 'true');}
    }

    public function getObject($key){
        $object = $this->arrayConfigurationReader->getObjectFromContainer($key);
        if($object!= 'null'){
            Logger::debug(' >> Object [' . $key . '] retrieved');
            return $object;
        } else {
            Logger::debug(' >> [ERROR] Object [' . $key . '] does not exists');
            return null;
        }
    }
}
