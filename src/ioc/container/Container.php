<?php
include_once(dirname(__FILE__)  . '/../cache/ContainerCache.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Container   manage the instanced and configured objects and
 * add them to an array list.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class Container extends ContainerCache  {
    private $container = array();
    private $dummyVariable = 'null';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){ }

    /**
     * Add object to array list
     *
     * @param string $key Unique id that indentifies a defined object
     * @param object $value Instantiated object value
     * @return void
     */
    public function addObject($key, $value){
        $this->container[$key] = $value;
    }

    /**
     * Retrieve a configured object directly from the  container
     *
     * @param string $key Unique id that indentifies a defined object
     * @return object
     */
    public function &getObject($key){
        $objectCached = $this->getObjectFromContainerCache($key);
        if($objectCached != null){
            return $objectCached;
        } else {
            if (isset($this->container[$key])){
                return $this->container[$key];
            }else {
                return $this->dummyVariable;
            }
        }
    }

    /**
     * Cache container objects
     *
     * @return void
     */
    public function cacheContainer(){
        foreach ($this->container as $key => $value){
            $this->cacheObject($key, $value);
        }
    }
}