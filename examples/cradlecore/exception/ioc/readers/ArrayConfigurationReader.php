<?php
include_once(dirname(__FILE__)  . '/Reader.php');
include_once(dirname(__FILE__)  . '/../container/Container.php');
include_once(dirname(__FILE__)  . '/../factory/Factory.php');
include_once(dirname(__FILE__)  . '/../includes/IncludeManager.php');
include_once(dirname(__FILE__)  . '/ArraySourceFilesReader.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ArrayConfigurationReader
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */
class ArrayConfigurationReader extends Factory implements Reader {

    private $configuration;

    function __construct($configuration){
        $this->configuration = $configuration;
        $this->instanceContainer(new Container());
        if(!$this->isContainerCached(10)){
            $this->read();
            $this->cacheContainer();
        } else {
            $arraySourceFilesReader = new ArraySourceFilesReader();
            //IncludeManager::includeFiles( $xmlSourceFilesReader->getObjectsDefinitionArray($configurations));
        }
    }

    /**
     * Start reading the configuration array
     *
     *
     */
    public function read(){
        for($i = 0; $i < count($this->configuration); $i++){
             $this->addObject($this->configuration[$i]);
        }
    }

    /**
     * Start the proccess to add an object to the container
     *
     *
     */
    public function addObject($object){
        $this->addObjectToContainer($object);
    }

    /**
     * Start the proccess to retrieve an object from the container
     *
     *
     */
    public function getObjectFromContainer($key){
        return $this->getObject($key);
    }

}