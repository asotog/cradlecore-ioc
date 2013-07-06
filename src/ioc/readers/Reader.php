<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Reader      functions to be implemented by reader classes.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

interface Reader {
    public function read();
    public function addObject($object);
    public function getObjectFromContainer($key);
}
?>
