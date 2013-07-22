---
layout: docs
title: Class properties dependency injection
prev_section: home
next_section: quickstart
permalink: /docs/properties_injection/
---

With Cradlecore IOC, dependency injection is available for classes properties, this means that an object properties can be injected 
with other objects following [setter/getter access pattern](http://en.wikipedia.org/wiki/Mutator_method), this means that a class should provide the setter/getter methods to update the object property.

For instance, if you have a class like:
{% highlight php %}
<?php
class InvoiceDao {
    private $connection;
    
    public function __construct() {}
    
    public function getItems(){      
        return '[' . $this->connection->getDatabase()  . '] Items List';
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
