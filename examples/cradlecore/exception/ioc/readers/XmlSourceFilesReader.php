<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * XmlSourceFilesReader 	Simple xml reader.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */
class XmlSourceFilesReader {

    private $objects = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){ }

    /**
     * start reading the xml file and searchs the defined object tags
     *
     * @param string/array $configurations Single configuration file location or multiple locations in an array
     * @return array Array of XmlObjectTag objects with the defined properties
     */
    public function getObjectsDefinitionArray($configurations){
        $this->read($configurations);
        return $this->objects;
    }

    /**
     * start reading the xml file
     *
     * @param string/array $configurations Single configuration file location or multiple locations in an array
     * @return void
     */
    private function read($configurations){
        if(is_array($configurations)){
            echo 'Is array'; //iterate over strings array
        } else {
            $this->iterateFile($configurations);
        }
    }

    /**
     * iterate over the xml structure
     *
     * @param string $filename Configuration filename
     * @return void
     */
    private function iterateFile($filename){
        $xml = $this->getXml($filename);
        foreach ($xml->children() as $node) {
            switch($node->getName()){
                case 'object' : 
                    $this->isObject($node);
                    break;
                case 'import' : 
                    $this->isImport($node);
                    break;
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
     * parse the object tag
     *
     * @param SimpleXmlElement $node Object xml definition
     * @return void
     */
    private  function isObject($node){
        $attributes = $node->attributes();
        $xmlObject = new XmlObjectTag($attributes);
        $this->objects[] = $xmlObject;
    }

    /**
     * Open the file and return the xml
     *
     * @param string $filename $node Definition filename
     * @return SimpleXMLElement
     */
    public function getXml($filename) {
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);
        return new SimpleXMLElement($contents);
    }
}