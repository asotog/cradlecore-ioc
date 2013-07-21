<?php

/**
 * Constants used along the library code
 * 
 * @author alejandrosoto
 *
 */
class Constants {
    /**
     * Xml attributte used to define where is the main classes repository located
     * 
     * @var String
     */
    public static $CLASSPATH_LIST = 'classpath';
    
    /**
     * Xml attributte used to define the life time of the container
     *
     * @var String
     */
    public static $LIFETIME = 'container-lifetime';
    
    /**
     * Xml attributte used to define the context id 
     *
     * @var String
     */
    public static $CONTEXT_ID = 'context-id';
    
    /**
     * Xml attributte used to define the classname
     *
     * @var String
     */
    public static $CLASS = 'class';
    
    /**
     * Xml attributte used to define the php file of the class when class name and file are not the same
     *
     * @var String
     */
    public static $RESOURCE = 'resource';
    
    /**
     * Xml attributte used to define the objects id
     *
     * @var String
     */
    public static $ID = 'id';
    
    /**
     * Xml tag used to define object constructor arguments
     *
     * @var String
     */
    public static $ARGUMENT = 'constructor-argument';

    /**
     * Xml tag used to define object properties
     *
     * @var String
     */
    public static $PROPERTY = 'property';
    
    /**
     * Xml tag used to define object
     *
     * @var String
     */
    public static $OBJECT = 'object';
    
    /**
     * Xml tag used to define imports of configurations files
     *
     * @var String
     */
    public static $IMPORT = 'import';
    
    /**
     * Class separator on class path attributte on xml configuration
     *
     * @var String
     */
    public static $CLASS_SEPARATOR = ';';
    
    /**
     * PHP extension
     *
     * @var String
     */
    public static $PHP_EXTENSION = '.php';
    
    /**
     * Directory separator
     *
     * @var String
     */
    public static $DIR_SEPARATOR = '/';
    
    /**
     * Object properties key
     *
     * @var String
     */
    public static $PROPERTIES = 'properties';
    
    /**
     * Object arguments key
     *
     * @var String
     */
    public static $ARGUMENTS = 'constructor-arguments';
}