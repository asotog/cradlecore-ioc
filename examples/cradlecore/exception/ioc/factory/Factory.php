<?php
include_once(dirname(__FILE__)  . '/../checkers/ClassChecker.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Factory     Instance the defined objects from the xml file.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class Factory extends ClassChecker  {
    private  $container;

    /**
     * Constructor
     *
     * @return void
     */
    public function  __construct(){

    }

    /**
     * Instance a container object
     *
     * @param Container $container Objects container
     * @return void
     */
    public function instanceContainer($container){
        $this->container = $container;
    }

    /**
     * Instance object and start adding to container
     *
     * @param array $object Object properties 
     * @return void
     */
    public function addObjectToContainer($object){
        $correctClass = $this->isCorrectClass($object);
        if($correctClass == true){
            $this->createObjectInstance($object);
            $this->addPropertyToObjectInstance($object);
        } else if ($correctClass == false) {
            new CradleCoreException($object['class'] . ' is not a valid class');
            Logger::debug($object['class'] . ' is not a valid [CLASS] [ERROR]');
        }
    }

    /**
     * Add property to initialized object in the container
     *
     * @param array $object Object properties 
     * @return void
     */
    private function addPropertyToObjectInstance($object){
        if(count($object['properties']) > 0){
            for($i = 0; $i < count($object['properties']); $i++) {
                foreach ($object['properties'][$i] as $property => $value){
                    $this->addProperty($this->container->getObject($object['id']),
                    $property, $value);
                }
            }
        }
    }

    /**
     * Add the Property
     *
     * @param object $object Instanced object 
     * @param string $property Property name to be set 
	 * @param object $propertyValue property value 
     * @return void
     */
    private function addProperty(&$object,$property,$propertyValue){
        if($this->propertyCheck($object,$property)){
            $propertyName = $this->propertyFormat($property,$isReference);
            if($isReference){
                $result = call_user_func(array($object, 'set' . $propertyName),
                $this->container->getObject($propertyValue));
            } else {
                $result = call_user_func(array($object, 'set' . $propertyName),
                $propertyValue);
            }
            $this->dependencyFunctionCallResult($result, $propertyName);
        }
    }

    /**
     * For logging the result of the function called to set the dependency
     *
     * @param boolean $result True if the property set was successfull 
	 * @param string $propertyName Property name 
	 * @return void
     */
    private function dependencyFunctionCallResult($result,$propertyName){
        if($result!=null){
            new CradleCoreException('error when trying to set property ' .  $propertyName);
            Logger::debug(' .... [ERROR] when trying to set property  [' .  $propertyName . ']');
        } else {
            Logger::debug(' .... property set [' .  $propertyName . ']');
        }
    }

    /**
     * Performs a reflection to instance the object with all the configured values
     *
	 * @param array $object Object array 
	 * @return void
     */
    private function createObjectInstance($object){
        if(count($object['constructor-arguments']) > 0){
            $this->instanceObjectWithArguments($object);
        } else {
            $this->instanceSingleObject($object);
        }
    }

    /**
     * Instance an object without arguments
     *
	 * @param array $object Object array 
	 * @return void
     */
    private function instanceSingleObject($object){
        $reflectionClass = new ReflectionClass($this->getClassName($object));
        $objectInstanced = $reflectionClass->newInstance();
        Logger::debug(' Object [' . $object['id'] . '] type [' . $object['class'] .  '] instanced');
        $this->container->addObject($object['id'],$objectInstanced);
    }

    /**
     * Instance an object with arguments
     *
	 * @param array $object Object array 
	 * @return void
     */
    private function instanceObjectWithArguments($object){
        $reflectionClass = new ReflectionClass($this->getClassName($object));
        $objectInstanced = $reflectionClass->newInstanceArgs($this->proccessArguments($object['constructor-arguments']));
        Logger::debug(' Object [' . $object['id'] . '] type [' . $object['class'] .  '] instanced');
        $this->container->addObject($object['id'],$objectInstanced);
    }

    /**
     *  Make an ordered array of values for the contructor arguments
     *
	 * @param array $arguments Constructor arguments array 
	 * @return void
     */
    private function proccessArguments($arguments){
        $processedArguments = array();
        foreach ($arguments as $argument) {
            foreach ($argument as $value){
                $this->getArgument($argument, $value, $processedArguments);
            }
        }
        return $processedArguments;
    }

    /**
     *  Set the values depending on if it is referenced object or simple value
     *
	 * @param array $argument Constructor argument array
	 * @param string $value Value string 
	 * @param array $argumentsArray Referenced array to store the arguments 
	 * @return void
     */
    private function getArgument($argument, $value, &$argumentsArray){
        if(array_key_exists('<ref>', $argument)){
            $argumentsArray[] = $this->container->getObject($value);
        } else {
            $argumentsArray[] = $value;
        }
    }

    /**
     * Determine if there is an already objects container loaded in cache
     *
	 * @return boolean
     */
    public function isContainerCached($lifetime) {
        $this->container->setCacheLifeTime($lifetime);
        if($this->container->isCached(getenv('MEMORYID'))){
            Logger::debug('===================================================');
            Logger::debug(' Container was previously configurated...');
            return true;
        } else {
            Logger::debug('===================================================');
            Logger::debug(' Instantiating container...');
            return false;
        }
    }

    /**
     * Start to cache the container
     *
  	 * @return void
     */
    public function cacheContainer() {
        $this->container->cacheContainer();
    }

    /**
     * Return a configured object retrieved from the container
     *
     * @param string $key Unique id that indentifies a defined object
     * @return object
     */
    public function getObject($key){
        return $this->container->getObject($key);
    }
}
?>