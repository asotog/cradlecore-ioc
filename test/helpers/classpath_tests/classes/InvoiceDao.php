<?php
class InvoiceDao {
    private $connection;
    
    public function __construct($connection){
       $this->connection = $connection; 
    }
    
    public function getItems(){      
        return '[' . $this->connection->getDatabase()  . '] Items List';
    }
}