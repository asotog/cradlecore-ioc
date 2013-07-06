<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * PropertiesFile  Reads and parses a properties file like in java.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */
class PropertiesFile {
    private $propertiesText;

    /**
     * Constructor
     *
     * @param string $propertiesText The text contained in an archive with formatted text 
     * with .properties style
     * @return void
     */
    public function __construct($propertiesText){
        $this->propertiesText = $propertiesText;
    }

    /**
     * Return all keys/values from an archive with formatted text with .properties style
     *
     * @return array
     */
    public function getProperties(){
        $result = array();
        $lines = explode("\n", $this->propertiesText);
        $key = "";
        $isWaitingOtherLine = false;
        foreach ($lines as $i => $line) {
            if (empty($line) || (!$isWaitingOtherLine && strpos($line, "#") === 0))
            continue;

            if (!$isWaitingOtherLine) {
                $key = substr($line, 0, strpos($line, '='));
                $value = substr($line, strpos($line, '=')+1, strlen($line));
            }
            else {
                $value .= $line;
            }

            /* Check if ends with single '\' */
            if (strrpos($value, "\\") === strlen($value)-strlen("\\")) {
                $value = substr($value,0,strlen($value)-1)."\n";
                $isWaitingOtherLine = true;
            }
            else {
                $isWaitingOtherLine = false;
            }

            $result[$key] = $value;
            unset($lines[$i]);
        }

        return $result;
    }

}