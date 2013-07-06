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
 * IncludeManager 	Include php files task manager.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class IncludeManager {

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct(){

    }

    /**
     * Include source files when the container is cached
     *
     * @param array $objectsArray XmlObjectTag array
     * @return void
     */
    public static function includeFiles($objectsArray = array()){
        $classChecker = new ClassChecker();
        for($i = 0; $i < count($objectsArray); $i++){
            $result = $classChecker->isCorrectClass($objectsArray[$i]->getObjectConfiguration());
        }
        unset($classChecker);
    }
}