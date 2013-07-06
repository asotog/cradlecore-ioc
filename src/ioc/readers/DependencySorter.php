<?php
/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * DependencySorter   orders all the objects definitions according to its
 * dependencies requirements for example to avoid put in sorted order the objects defined in the configuration.xml
 * this class  builds a graph in an array depending on the dependencies level of the xml definitions.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class DependencySorter {
    private $tempStorage = array();

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct() { }

    /** 
     * Adds an object definition to the sorter temp storage
     *
     * @param array $object Custom array where is defined an object
     * @return void
     */
    public function addToTempStorage($objectDefinition) {
        $this->tempStorage[] = $objectDefinition;
    }

    /**
     * Retrieves the sorted objects definition
     *
     * @return array An array with all the objects definitions sorted by dependencies order
     */
    public function retrieveSortedObjectsDefinitions() {
        $this->sortObjectsDefinitions();
        return $this->tempStorage;
    }

    /**
     * Builds a dependency graph into an array according to the objects dependencies
     *
     * @return void
     */
    private function sortObjectsDefinitions() {
        $dependenciesExists = true;
        for ($j = 0; $j < count($this->tempStorage); $j++) {
            for ($i = 0; $i < count($this->tempStorage); $i++) {
                $dependencies1 = $this->checkArgumentsDependencies($this->tempStorage[$i]['constructor-arguments']);
                $dependencies2 = $this->checkPropertiesDependencies($this->tempStorage[$i]['properties']);
                $dependenciesExists = $this->dependenciesPrevioulyExists(array_merge($dependencies1,$dependencies2), $i);
                $this->moveObjectDefinition($dependenciesExists, $i);
            }
        }
        $this->isDefinitionSorted($dependenciesExists);
    }
    
    /**
     * 
     * Validates if the objects are sorted if not, call the order function
     * @param boolean $objectsOrdered
     * @return void
     */
    private function isDefinitionSorted($objectsOrdered) {
        if(!$objectsOrdered) {
            $this->sortObjectsDefinitions();
        };
    }
    
    /**
     * 
     * If has dependencies and requires sort it swaps (kind of push) all the objects and sends the current object to the end
     *
     * @param boolean $dependenciesExists Flag to represent if the current item has dependencies that exists before in the array
     * @param integer $index Current item array index
     * @return void
     */
    private function moveObjectDefinition($dependenciesExists, $index){
        if (!$dependenciesExists) {
            $temp = $this->tempStorage[$index];
            $lastIndex = count($this->tempStorage)  - 1;
            if ($this->tempStorage[$lastIndex] != $temp) {
                for ($i = $index; $i < count($this->tempStorage); $i++) {
                    $nextIndex = $i + 1;
                    if (isset($this->tempStorage[$nextIndex])) {
                        $this->tempStorage[$i] = $this->tempStorage[$nextIndex];
                    }
                }
                $this->tempStorage[$lastIndex] = $temp;
            }          
        }
    }

    /**
     * 
     * Checks if the current item has dependecies in the previous places in the array
     *
     * @param boolean $dependencies Flag to represent if the current item has dependencies in the array
     * @param integer $index Current item array index
     * @return boolean
     */
    private function dependenciesPrevioulyExists($dependencies,$index) {
        $dependenciesExists = true;
        for ($i = 0; $i < count($dependencies); $i++) {
            $dependenciesExists = $this->dependencyExistsBefore($dependencies[$i], $index);
        }
        return $dependenciesExists;
    }    
    
    /**
     *
     * Checks and retrieve the dependencies for the object definition arguments
     *
     * @param array $arguments Arguments defined in an array
     * @return array     
     */
    private function checkArgumentsDependencies($arguments){
        $dependencies = array();
        for ($i = 0; $i < count($arguments); $i++) {
            foreach ($arguments[$i] as $key => $value) {
                if($key == '<ref>' && $this->dependencyExists($value)) {
                        $dependencies[] = $value;
                }
            }
        }
        return $dependencies;
    }

    /**
     *
     * Checks and retrieve the dependencies for the object definition properties
     *
     * @param array $properties Properties defined in an array
     * @return array     
     */
    private function checkPropertiesDependencies($properties) {
        $dependencies = array();
        for ($i = 0; $i < count($properties); $i++) {
            foreach ($properties[$i] as $key => $value) {
                if(stripos($key,':ref') !== false) {
                    $dependencies[] = $value;
                }
            }
        }
        return $dependencies;
    }

    /**
     *
     * Checks if a dependency exists in previous objects defined in the array
     *
     * @param string $id Object id
     * @param integer $index Array index
     * @return boolean 
     */
    private function dependencyExistsBefore($id, $index) {
        for ($i = 0; $i < count($this->tempStorage); $i++) {
            if ( $this->tempStorage[$i]['id'] == $id && $index > $i  ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 
     * Checks if a dependency exists in a wherever position in the array
     *
     * @param string $id Object id
     * @return boolean 
     */
    private function dependencyExists($id) {
        for ($i = 0; $i < count($this->tempStorage); $i++) {
            if ( $this->tempStorage[$i]['id'] == $id ) {
                return true;
            }
        }
        return false;
    }
}
