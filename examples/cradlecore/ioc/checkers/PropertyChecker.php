<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * PropertyChecker	 validate if the property is valid and if it has the
 * proper getter and setter respective method.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class PropertyChecker {

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){}

    /**
     * Checks if it is a valid property and if it exists
     *
     * @param SimpleXMLElement $object Xml object
     * @param string $propertyName Name of the object property
     * @return boolean
     */
    public function propertyCheck($object, $propertyName){
        return $this->propertyExists($object, $propertyName);
    }

    /**
     * Checks if property exists
     *
     * @param SimpleXMLElement $object Xml object
     * @param string $propertyName Name of the object property
     * @return boolean
     */
    private function propertyExists($object, $propertyName){
        if($this->customPropertyExists($object, $this->propertyFormat($propertyName)) &&
        $this->existsGetterAndSetter($object, $this->propertyFormat($propertyName))){
            return true;
        }
        new CradleCoreException( $this->propertyFormat($propertyName) . ' is not a valid property name or does not has getter/setter functions');
        Logger::debug(' .... [ERROR] ' . $this->propertyFormat($propertyName) . ' is not a valid property name or does not has getter/setter functions');
        return false;
    }

    /**
     * Custom property exists to cover PHP versions like 5.2   and fix the problem with
     * private properties reflection
     *
     * @param SimpleXMLElement $object Xml object
     * @param string $propertyName Name of the object property
     * @return boolean
     */
    private function customPropertyExists($object,$propertyName){
        $exists = property_exists($object, $propertyName);
        if (!$exists) {
            $objectInstance = new ReflectionClass($object);
            $exists = $objectInstance->hasProperty($propertyName);
        }
        return $exists;
    }

    /**
     * Checks if the property has getter and setter functions
     *
     * @param SimpleXMLElement $object Xml object
     * @param string $propertyName Name of the object property
     * @return boolean
     */
    private function existsGetterAndSetter($object, $propertyName){
        if(method_exists($object, 'set' . $propertyName) &&
        method_exists($object, 'get' .  $propertyName)) {
            return true;
        }
        return false;
    }

    /**
     * Search for a referenced property string pattern
     *
     * @param string $propertyName Name of the object property
     * @param boolean/null $isReference Flag to be set to define when is a property referenced value
     * @return boolean
     */
    public function propertyFormat($propertyName, &$isReference = null){
        $matchesarray = array();
        preg_match('/.*:ref/', $propertyName, $matchesarray);
        if(count($matchesarray) > 0){
            $array = explode( ':', $matchesarray[0]);
            $propertyName = $array[0];
            $isReference = true;
            return $propertyName;
        }
        $isReference = false;
        return $propertyName;
    }
}