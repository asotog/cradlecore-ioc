<?php

/*
 * This file is part of the cradlecore For PHP package.
 * (c) Alejandro Soto Gonzalez. <asotog88@yahoo.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Context define all the functions to be implemented in XmlContext Class.
 *
 * @author     Alejandro Soto Gonzalez <asotog88@yahoo.es>
 */

interface Context {
	public function initialize();
    public function getObject($id);
}
?>