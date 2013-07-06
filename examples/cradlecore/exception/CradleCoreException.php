<?php
/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * CradleCoreException Custom framework exception, just an object to throw messages
 * when something goes wrong.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

class CradleCoreException  {

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($message) {
        $title = 'CradleCoreException';
        if (php_sapi_name() != 'cli') {
            $title = '<b>' . $title . '</b>';
        }
        echo "\n" . $title  .": " . $message . "\n";
    }
}
?>