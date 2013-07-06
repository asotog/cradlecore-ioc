<?php
include_once(dirname(__FILE__)  . '/DependencySorter.php');
include_once(dirname(__FILE__)  . '/Reader.php');
include_once(dirname(__FILE__)  . '/../container/Container.php');
include_once(dirname(__FILE__)  . '/../factory/Factory.php');
include_once(dirname(__FILE__)  . '/XmlObjectTag.php');
include_once(dirname(__FILE__)  . '/XmlSourceFilesReader.php');
include_once(dirname(__FILE__)  . '/../includes/IncludeManager.php');
include_once(dirname(__FILE__)  . '/../../exception/CradleCoreException.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * XmlConfigurationReader 	reads the xml file.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class XmlConfigurationReader extends Factory implements Reader {
    private $configurationFiles;
    private $dependencySorter;

    /**
     * Constructor
     *
     * @param string/array $configurations Single configuration file location or multiple locations in an array
     * @return void
     */
    public function  __construct($configurations){
        $this->configurationFiles = $configurations;
        $this->instanceContainer(new Container());
        if(!$this->isContainerCached($this->retrieveCacheLifetime($this->retrieveConfigurationFilename()))){
            $this->dependencySorter = new DependencySorter();
            $this->read();
            $this->addSortedObjectsToContainer();
            $this->cacheContainer();
        } else {
            $xmlSourceFilesReader = new XmlSourceFilesReader();
            IncludeManager::includeFiles( $xmlSourceFilesReader->getObjectsDefinitionArray($configurations));
        }
    }
    
    /**
     * Iterates over the sorted array containing the objects definitions and add the objects to the container
     *
     * @return void
     */
    private function addSortedObjectsToContainer() {
        $objectsGraph = $this->dependencySorter->retrieveSortedObjectsDefinitions();   
        for ($i = 0; $i < count($objectsGraph); $i++) {
            $this->addObject($objectsGraph[$i]);
        };
    }
    
    /**
     * Retrieves the configuration filename
     *
     * @return string
     */
    private function retrieveConfigurationFilename() {
         if(is_array($this->configurationFiles)){
            return $this->configurationFiles[0];
        } else {
            return $this->configurationFiles;
        }
    }
    
    /**
     * Start reading xml definition file
     *
     * @return void
     */
    public function read(){
        if(is_array($this->configurationFiles)){
            echo 'Is array'; //iterate over strings array
        } else {
            $this->iterateFile($this->configurationFiles);
        }
    }


    /**
     * Open the file and return the xml
     *	
     * @param string $filename Configuration file absolute location
     * @return SimpleXMLElement
     */
    private function getXml($filename) {
        if (file_exists($filename)) {
            $contents = $this->openConfigurationFile($filename);
            if (!$this->isValidXmlDocument($contents)) {
                return null;
            }
            return new SimpleXMLElement($contents);
        } else {
            new CradleCoreException('File ' . $filename . ' does not exists');
            return null;
        }
    }

    /**
     * Open the configuration file
     *
     * @param string $filename Configuration file absolute location
     * @return string File contents
     */
    private function openConfigurationFile($filename) {
        $handle = fopen($filename, "r");
        if (filesize($filename) > 0) {
            $contents = fread($handle, filesize($filename));
            fclose($handle);
            return $contents;
        }
        return '';   
    }
    
    /**
     * Validates the document syntax and structure
     *
     * @param string $contents Xml document contents
     * @return boolean
     */
    private function isValidXmlDocument($contents) {
        libxml_use_internal_errors(true);
        if(!simplexml_load_string($contents)){
            new CradleCoreException("Invalid XML Document");
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
            return false;
        }
        return true;
    }
    
    /**
     * Iterates the xml nodes from the configuration file
     *
     * @param string $filename Configuration file absolute location
     * @return void
     */
    private function iterateFile($filename){
        $xml = $this->getXml($filename);
        if ($xml != null) {
            foreach ($xml->children() as $node) {
                switch($node->getName()){
                    case 'object' : $this->isObject($node);
                    break;
                    case 'import' : $this->isImport($node);
                    break;
                }
            }
        }
    }
    
    /**
     * Retrieves the cache lifetime( milliseconds ) in the server for the objects container
     *
     * @param string $filename Configuration filename
     * @return integer
     */
    private function retrieveCacheLifetime($filename){
        $xml = $this->getXml($filename);
        if ($xml != null) {
            $lifetime = (string) $xml['container-lifetime'];
            if(is_numeric($lifetime)) {
                return $lifetime;
            } else {
                return 0;
            }
        }
    }

    /**
     * Repeats the xml iteration in the file that its been importing
     * 
     * @param SimpleXmlElement $node Xml object with the xml object definition
     * @return void
     */
    private function isImport($node){
        $attributes = $node->attributes();
        $this->iterateFile(getenv('CALLERDIR') . $attributes['source']);
    }


    /**
     * Instantiates a XmlObjectTag object and add it to the container
     *
     * @param SimpleXmlElement $node Xml object with the xml object definition
     * @return void
     */
    private  function isObject($node){
        $attributes = $node->attributes();
        $xmlObject = new XmlObjectTag($attributes);
        $this->objectChildren($node,$xmlObject);
        $this->dependencySorter->addToTempStorage($xmlObject->getObjectConfiguration());
    }

    /**
     * Iterate over object tag children searching for constructor-argument, properties, etc
     *
     * @param SimpleXmlElement $node Xml object with the xml object definition
     * @param XmlObjectTag $xmlObject Placeholder object to add the defined properties
     * @return void
     */
    private function objectChildren($node, &$xmlObject){
        foreach ($node->children() as $child) {
            switch($child->getName()){
                case 'constructor-argument' :
                    $xmlObject->addConstructorArgument($child);
                    break;
                case 'property' :
                    $xmlObject->addProperty($child);
            }
        }
    }

    /**
     * Start the proccess to add an object to the container
     *
     * @param array $object Custom array where is defined an object
     * @return void
     */
    public function addObject($object){
        $this->addObjectToContainer($object);
    }

    /**
     * Start the proccess to retrieve an object from the container
     *
     * @param string $key Id to retrieve the object
     * @return Object
     */
    public function getObjectFromContainer($key){
        return $this->getObject($key);
    }
}
?>
