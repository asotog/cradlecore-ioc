<?php
class MyService {
    private $personDao;
    private $itemDao;

    public function __construct(){ }

    public function getPersonDao(){
        return $this->personDao;
    }

    public function setPersonDao($personDao){
        $this->personDao = $personDao;
    }

    public function setItemDao($itemDao){
        $this->itemDao = $itemDao;
    }

    public function getItemDao(){
       return  $this->itemDao;
    }
}