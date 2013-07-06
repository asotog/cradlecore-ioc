<?php
class DBConnection {
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
	
	public function getDatabase(){
	    return $this->database;
	}
	
	public function setDatabase($database) {
	    $this->database = $database;
	}
}
?>