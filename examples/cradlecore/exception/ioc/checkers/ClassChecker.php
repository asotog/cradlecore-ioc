<?php
include_once(dirname(__FILE__)  . '/../checkers/PropertyChecker.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ClassChecker	 validate the class defined in the xml file.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class ClassChecker extends PropertyChecker {
    private $pearPath;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){  }

    /**
     * Checks if the class can be included or is a valid class
     *
     * @param array $object Array with the object properties and defined values
     * @return boolean
     */
    public function isCorrectClass($object){
        if($object['pear'] == 'true'){
            return $this->validPearClass($object);
        }else{
            return $this->validClass($object);
        }
    }

    /**
     * Validate non pear class
     *
     * @param array $object Array with the object properties and defined values
     * @return boolean
     */
    private function validClass($object){
        if(file_exists(getenv('CALLERDIR') . $this->getClassFile($object))){
            include_once(getenv('CALLERDIR') . '/' . $this->getClassFile($object));
            $this->getClassName($object);
            return class_exists($this->getClassName($object));
        } else {
            new CradleCoreException($this->getClassFile($object) . ' is not a valid class file');
            Logger::debug(' [ERROR] ' . $this->getClassFile($object) . ' is not a valid [CLASS FILE]');
        }

    }

    /**
     * Validate pear class
     *
     * @param array $object Array with the object properties and defined values
     * @return boolean
     */
    private function validPearClass($object){
        if($this->isValidPearFile($this->getClassFile($object))){
            return class_exists($this->getClassName($object));
        }else{
            new CradleCoreException($this->getClassFile($object) . ' is not a valid class file');
            Logger::debug(' [ERROR] ' . $this->getClassFile($object) . ' is not a valid [CLASS FILE]');
        }
    }

    /**
     * Get classname from the class attribute
     *
     * @param array $object Array with the object properties and defined values
     * @return string
     */
    public  function getClassName($object){
        preg_match('/\/[A-Za-z_]*$/', $object['class'], $match);
        if(count($match) > 0){
            return str_replace('/', '', $match[0]) ;
        } else {
            return $object['class'];
        }
    }
    /**
     * Returns the file that contains the class needed
     *
     * @param array $object Array with the object properties and defined values
     * @return string
     */
    private function getClassFile($object){
        if($object['resource']!= 'no'){
            return $object['resource'];
        } else {
            return $object['class'] . '.php';
        }
    }

    /**
     * Return if the file that includes the pear class is valid
     *
     * @param string $resource Filename or resource name
     * @return boolean
     */
    private function isValidPearFile($resource){
        $includePaths = explode(';', get_include_path());
        return $this->iterateOverIncludePath($includePaths,$resource);
    }

    /**
     * Iterate over includes paths searching the classfile
     *
     * @param array $includePaths Array of php includes paths
     * @param string $resource Filename or resource name
     * @return boolean <b>False</b> if it does not exist or <b>True</b> if it does.
     */
    private function iterateOverIncludePath($includePaths, $resource){
        for( $i = 0; $i < count($includePaths); $i++){
            if($this->includeFile($includePaths, $resource)) {
                return true;
            };
        }
        return false;
    }

    /**
     * Include php files in the runtime
     *
     * @param array $includePaths Array of php includes paths
     * @param string $resource Filename or resource name
     * @return boolean <b>False</b> if it does not exist or <b>True</b> if it does.
     */
    private function includeFile($includePaths, $resource){
        if(file_exists($includePaths[$i] . '/' . $resource)){
            include_once($includePaths[$i] . '/' . $resource);
            return true;
        }
        return false;
    }
}