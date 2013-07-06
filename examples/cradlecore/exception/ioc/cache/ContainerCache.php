<?php
include_once(dirname(__FILE__)  . '/../../utils/PropertiesFile.php');

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ContainerCache.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class ContainerCache {
    private $lifetime = 60;
    private $currentTime;
    private $propertiesFileUtil;
    private $storageLocation;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){  }

    /**
     * Sets the container cache life time
     * 
     * @param integer $lifetime Container lifetime (in seconds) in cache
     * @return void
     */
    public function setCacheLifeTime($lifetime = 10){
        $this->lifetime = $lifetime;
    }

    /**
     * Cache an object
     *
     * @param string $key Unique id that indentifies a defined object
     * @param object $value Instantiated object value
     * @return void
     */
    public  function cacheObject($key, $value){
        $file = fopen($this->storageLocation, 'a');
        fputs($file, $key . "=" . base64_encode(gzcompress(serialize($value))) . "\r\n");
        fclose($file);
    }

    /**
     * Checks if its cached
     *
     * @param string $prefix Unique id/filename that indentifies the container cache file
     * @return boolean
     */
    public function isCached($prefix){
        $this->storageLocation = dirname(__FILE__) . '/storage/' . $prefix . '.data';
        if(file_exists($this->storageLocation)){
            $this->currentTime = (time()  - fileatime($this->storageLocation));
            return $this->checkCache($prefix);
        }
        else {
            return false;  }
    }

    /**
     * Checks if cache expired
     *
     * @param string $prefix Unique id/filename that indentifies the container cache file
     * @return boolean
     */
    private function checkCache($prefix){
        if($this->currentTime > $this->lifetime){
            $this->flushCache($prefix);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Flush the cache and clear all
     *
     * @param string $prefix Unique id/filename that indentifies the container cache file
     * @return void
     */
    private function flushCache($prefix){
        if(file_exists($this->storageLocation)){
            $result = unlink($this->storageLocation);
        }
    }

    /**
     * Get object from cached container
     *
     * @param string $key Unique id that indentifies a defined object
     * @return object
     */
    public function getObjectFromContainerCache($key){
        $container = $this->getPropertiesTool()->getProperties();
        if(isset($container[$key])){
            Logger::debug(' cache was read...');
            return unserialize(gzuncompress(base64_decode($container[$key])));
        }
        else {
            Logger::debug(' object in cache not found...');
            return null;
        }
    }

    /**
     * Get object to manage properties  formatted file
     *
     * @return PropertiesFile
     */
    private function getPropertiesTool(){
        if(!isset($this->propertiesFileUtil)){
            if(file_exists($this->storageLocation)){
                $file = fopen($this->storageLocation, 'r');
                $data = fread($file, filesize($this->storageLocation));
                fclose($file);
            }
            else {
                $data = 'none';
            }
            $this->propertiesFileUtil = new PropertiesFile($data);
        }
        return $this->propertiesFileUtil;
        return null;
    }
}
?>