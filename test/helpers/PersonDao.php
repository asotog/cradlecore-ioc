<?php
class PersonDao {
    private $name;
    private $connectionDb;

    public function __construct(){
        $this->name = 'success';
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getConnectionDb(){
        return $this->name;
    }

    public function setConnectionDb($connectionDb){
        $this->connectionDb = $connectionDb;
    }
    
    public function getConnection(){
       return  $this->connectionDb->getConnectionString();
    }
}
?>