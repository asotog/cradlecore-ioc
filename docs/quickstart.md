---
layout: docs
title: Quick-start guide
prev_section: installation
permalink: /docs/quickstart/
---

Ok, time to go, lets assume you have 2 classes, one depends on the other, for example EmployeeDao depends on DBConnection.

<u>EmployeeDao:</u>
{% highlight php %}
<?php
class EmployeeDao {
    private $connectionDb;

    public function __construct(){
    }

    public function getConnectionDb(){
        return $this->connectionDb;
    }

    public function setConnectionDb($connectionDb){
        $this->connectionDb = $connectionDb;
    }

    public function listEmployees(){
        $db = $this->connectionDb->getDbManager();
        $statement = $db->prepare('SELECT * FROM employee');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_NUM);
    }
}
?>
{% endhighlight %}

<u>DBConnection:</u>
{% highlight php %}
<?php
class DBConnection {
    private $connectionString;
    private $user;
    private $password;

    public function __construct($dbms,$host,$port,$db,$user,$password){
        $this->connectionString = $dbms . ':host=' . 
                                $host . ';dbname=' . $db;
        $this->user = $user;
        $this->password = $password;
    }

    public function getDbManager() {
        $dbm = new PDO($this->connectionString, 
                        $this->user, $this->password );
        return $dbm;
    }
}
{% endhighlight %}

So we have our 2 classes, as you can see a dao class needs a class that retrieves the connection to be able to query the list of employees,
and we are not instantiating the connection class in the dao class because our library is the only one that should take care of that one. Now lets move on to
the dependencies configuration required for this case. As you remember we said that Cradlecore IOC uses xml configurations to set up the objects:

<u>configuration.xml</u>
{% highlight xml %}
<?xml version="1.0" encoding="UTF-8"?>
<objects>

    <object id="dbConnection" class="DBConnection">
        <constructor-argument name="dbms">mysql</constructor-argument>
        <constructor-argument name="host">localhost</constructor-argument>
        <constructor-argument name="port">3306</constructor-argument>
        <constructor-argument name="db">mydb</constructor-argument>
        <constructor-argument name="user">admin1</constructor-argument>
        <constructor-argument name="password">1234</constructor-argument>
    </object>

    <object id="employeeDao" class="EmployeeDao">
        <property name="connectionDb" ref="dbConnection" />
    </object>

</objects>
{% endhighlight %}

Time to use this object and retrieve some employees:

<u>application.php</u>
{% highlight php %}
<?php
/* Library include */
include_once('/your_location/cradlecore/ioc/context/Context_Factory.php');
$callerDirectory = dirname(__FILE__);
/* Absolute location of the xml file */
$configuration = $callerDirectory . '/configurations/configuration.xml';
$context =  Context_Factory::getXmlContext($configuration);
/* Retrieve object from container */ 
$employeeDaoObject = $context->getObject('employeeDao');
$employees = $employeeDaoObject->listEmployees();
{% endhighlight %}

For this example currently we have in out directory structure something like this:

{% highlight bash %}
.
├── configurations
|   ├── configuration.xml
|   ├── DBConnection.php
|   └── EmployeeDao.php
└── application.php
{% endhighlight %}

As you can see our library is taking care of the objects and values that each object needs to be instantiated and ready to be used.