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
     * Class path list, list of location/paths where the classes are stored and where are going to be taken tp include the php files 
     * and to load the container object
     * 
     * @var array<String>
     */
    private $classPathList= array();
    
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){  }

    /**
     * Sets the class path list
     * 
     * @param array $classPathList
     */
    public function setClassPathList($classPathList) {
        $this->classPathList = $classPathList;
    }
    
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
        $file = $this->fileExists($object);
        if ($file !== false){
            include_once($file);
            $this->getClassName($object);
            return class_exists($this->getClassName($object));
        } else {
            new CradleCoreException($this->getClassFile($object) . ' is not a valid class file');
            Logger::debug(' [ERROR] ' . $this->getClassFile($object) . ' is not a valid [CLASS FILE]');
        }

    }

    /**
     * Class file exists validator
     * 
     * @param array $object Array with the object properties and defined values
     * @return boolean
     */
    private function fileExists($object){
        $file = getenv('CALLERDIR') . $this->getClassFile($object);
        if (!file_exists($file)) {
            for ($i = 0; $i < count($this->classPathList); $i++) {
                $file = getenv('CALLERDIR') . $this->classPathList[$i] . $this->getClassFile($object);
                if (file_exists($file)) {
                    return $file;
                }
            }
            return false;
        }
        return $file;
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
        preg_match('/\/[A-Za-z_]*$/', $object[Constants::$CLASS], $match);
        if(count($match) > 0){
            return str_replace(Constants::$DIR_SEPARATOR, '', $match[0]) ;
        } else {
            return $object[Constants::$CLASS];
        }
    }
    /**
     * Returns the file that contains the class needed
     *
     * @param array $object Array with the object properties and defined values
     * @return string
     */
    private function getClassFile($object){
        if($object[Constants::$RESOURCE] != 'no'){
            return $object[Constants::$RESOURCE];
        } else {
            return $object[Constants::$CLASS] . Constants::$PHP_EXTENSION;
        }
    }

    /**
     * Return if the file that includes the pear class is valid
     *
     * @param string $resource Filename or resource name
     * @return boolean
     */
    private function isValidPearFile($resource){
        $includePaths = explode(Constants::$CLASS_SEPARATOR, get_include_path());
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
            if($this->includeFile($includePaths[$i], $resource)) {
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
    private function includeFile($includePath, $resource){
        if(file_exists($includePath . Constants::$DIR_SEPARATOR  . $resource)){
            include_once($includePath . Constants::$DIR_SEPARATOR . $resource);
            return true;
        }
        return false;
    }
}