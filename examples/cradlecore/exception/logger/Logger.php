<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Logger log functionality to show debug messages.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class Logger {

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct(){ }

    /**
     * Log message in log file
     *
     * @param string $message Message to be logged in log files
     * @return void
     */
    public static function debug($message) {
        date_default_timezone_set('UTC');
        $message = "\r\n[CRADLECORE CONTAINER] [" . date('m/d/Y H:i:s') . "]"  . $message;
        if(getenv('DEBUG') == 'true'){
            $file = fopen(getenv('CALLERDIR') . '/cradlecore.log', 'a');
            fwrite($file,  $message);
            fclose($file);
        }
    }
}
?>