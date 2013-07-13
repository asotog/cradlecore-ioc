<?php
class Validator {
    public function  __construct() {
        
    }
    public function isEmpty($stringValue) {
        return !(strlen($stringValue) > 0);
    }
    
}