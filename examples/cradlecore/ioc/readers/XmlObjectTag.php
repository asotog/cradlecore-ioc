<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * XmlObjectTag represents the definition from the xml structure for objects.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class XmlObjectTag {
    private $attributes_defined;
    private $currentAttributes;
    
    
    /**
     * Constructor
     *
     * @param SimpleXmlElement $attributes The object xml tag attributes
     * @return void
     */
    public function __construct($attributes){
        $this->attributes_defined = array(
            'id' => '',
            'class' => '',
            'resource' => 'no',
            'pear' => 'false',
        	'constructor-arguments' => array(),
        	'properties' => array()
        );
        $this->currentAttributes = $attributes;
        $this->defineObjectTag();
    }

    /**
     * Retrieves an array with the strings for the xml properties and attributes 
     *
     * @return array
     */
    public function getObjectConfiguration() {
        return $this->attributes_defined;
    }

    /**
     * Set the single values for the object
     *
     * @return void
     */
    private function defineObjectTag() {
        foreach($this->attributes_defined as $attribute => $value){
            if(isset($this->currentAttributes[$attribute])){
                $this->attributes_defined[$attribute] = (string) $this->currentAttributes[$attribute];
            }
        }
    }

    /**
     * Add item to objects constructor-arguments array
     *
     * @param SimpleXmlElement $attributes The xml tags for contructor arguments
     * @return void
     */
    public  function addConstructorArgument($argument){
        foreach ($argument->children() as $child){
            if($child->getName()=="ref"){
                $this->attributes_defined['constructor-arguments'][] = 	array('<ref>' => (string) $child[0]['id']);
                return;
            }
        };
        $this->attributes_defined['constructor-arguments'][] = 	array($argument['name'] . '' => (string) $argument[0]);
    }

    /**
     * Validates the attributes in the xml structure
     *
     * @param SimpleXmlElement $attributes The xml tags for contructor arguments
     * @return boolean
     */
    private function validateArgument($argument){
        return true;
    }

    /**
     * Add item to objects properties array
     * 
     * @param SimpleXmlElement $property The xml tags for object properties
     * @return void
     */
    public  function addProperty($property){
        if(isset($property['name'])){
            $this->setProperty($property);
        } else {
            Logger::debug(' "name" attribute is not defined [ERROR]');
        }
    }

    /**
     * Set  the property as an item in the array of properties
     *
     * @param SimpleXmlElement $property The xml tags for object properties
     * @return void
     */
    private function setProperty($property){
        if(isset($property['ref'])){
            $this->attributes_defined['properties'][] = array($property['name'] . ':ref' => (string) $property['ref']);
        } else {
            $this->validateProperty($property);
        }
    }

    /**
     * Validate the properties in the xml structure
     *
     * @param SimpleXmlElement $property The xml tags for object properties
     * @return void
     */
    private function validateProperty($property){
        if(isset($property[0]) && (0 != strlen($property[0]))){
            $this->attributes_defined['properties'][] = array($property['name'] . '' => (string) $property[0]);
        } else {
            Logger::debug(' [ERROR] no value define for [' . $property['name'] .  '] property');
        }
    }
}