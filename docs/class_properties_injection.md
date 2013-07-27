---
layout: docs
title: Class properties dependency injection
prev_section: home
next_section: quickstart
permalink: /docs/properties_injection/
---

With Cradlecore IOC, dependency injection is available for classes properties, this means that an object properties can be injected 
with other objects following [setter/getter access pattern](http://en.wikipedia.org/wiki/Mutator_method), this means that a class should provide the setter/getter methods to update the object property. Cradlecore IOC allows to set up a property with a [simple value](#simple-values) or with an already [configured object passed as refence](#object-reference):

<h2 id="object-reference">Configured object passed as reference</h2>

For instance, if you have a class like:
{% highlight php %}
<?php
class Connection {
	private $host;
	private $user;
	private $password;
	private $database;
	
	public function __construct($host,$user,$password,$database){
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
	}
	
	public function getConnectionString(){
		return 'host=' . $this->host . ';user=' . $this->user . 
			   ';password=' . $this->password . ';database=' . $this->database;
	}
}
?>
{% endhighlight %}

To inject a connection object to an object instanced with InvoiceDao class, the InvoiceDao class should be modified to follow the getter/setter pattern, as the following code illustrates:

{% highlight php %}
<?php
class InvoiceDao {
    private $connection;
    
    public function __construct() {}
    
    public function getItems(){      
        return '[' . $this->connection->getDatabase()  . '] Items List';
    }
    
    /**
     * Getter method for connection property
     * 
     * @return Connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Setter method for connection property
     * 
     * @param Connection
     */
    public function setConnection($connection) {
        $this->connection = $connection;
    }
}
?>
{% endhighlight %}

To finish this case, for objects configuration in the xml the property tag nested in the object tag is required to inject objects properties:

{% highlight xml %}
<?xml version="1.0" encoding="UTF-8"?>
<objects>
    <object id="connectionObject" class="Connection">
        <constructor-argument name="host">127.0.0.1</constructor-argument>
        <constructor-argument name="user">root</constructor-argument>
        <constructor-argument name="password">1234</constructor-argument>
        <constructor-argument name="database">dbtest</constructor-argument>
    </object>
    <object id="invoiceData" class="InvoiceDao">
        <property name="connection" ref="connectionObject" />
    </object>
</objects>
{% endhighlight %}

<h2 id="simple-values">Simple values</h2>

Additionally simple values also can be passed in the properties of the object configuration, for example you can have something as the following code illustrates, and initializes the properties passing hardcoded values, instead of objects reference as the previous example demostrated.

Ok, so having a class like this:

{% highlight php %}
<?php
class DBConnection {
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;

    public function __construct() {
    }

    public function getHost(){
        return $this->host;
    }

    public function setHost($host){
        $this->host = $host;
    }

    public function getPort(){
        return $this->port;
    }

    public function setPort($port){
        $this->port = $port;
    }
    public function getDatabase(){
        return $this->database;
    }

    public function setDatabase($database){
        $this->database = $database;
    }
    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
    }
    
    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }
}
?>
{% endhighlight %}

Can be configured as :

{% highlight xml %}
<?xml version="1.0" encoding="UTF-8"?>
<objects context-id="sample-app">
    <object id="employeesDBConnection" class="DBConnection">
        <property name="host">190.0.0.1</property>
        <property name="port">3006</property>
        <property name="database">employees</property>
        <property name="username">tests</property>
        <property name="password">1234</property>
    </object>
</objects>
{% endhighlight %}

