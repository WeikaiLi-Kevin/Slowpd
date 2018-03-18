<?php

class WebsiteUser{    
    protected static $DB_HOST = "127.0.0.1";  
    protected static $DB_USERNAME = "slowpd"; 
    protected static $DB_PASSWORD = "password"; 
    protected static $DB_DATABASE = "slowpd";
    
    private $id;
    private $studentnumber;
    private $password;
  
    private $mysqli;
    private $dbError;
    private $authenticated = false;
    
    function __construct() {
        $this->mysqli = new mysqli(self::$DB_HOST, self::$DB_USERNAME, 
                self::$DB_PASSWORD, self::$DB_DATABASE);
        if($this->mysqli->errno){
            $this->dbError = true;
        }else{
            $this->dbError = false;
        }
    }
    public function authenticate($studentnumber, $password){
        $loginQuery = "SELECT * FROM studentlist WHERE studentnumber = ? AND password = ?";
        $stmt = $this->mysqli->prepare($loginQuery);
        $stmt->bind_param('ss', $studentnumber, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $this->studentnumber = $studentnumber;
            $this->password = $password;
          
		  
            $this->authenticated = true;
                   
            $stmt->execute();
        }
        $stmt->free_result();
    }
    public function isAuthenticated(){
        return $this->authenticated;
    }
    public function hasDbError(){
        return $this->dbError;
    }
    public function getID(){
        return $this->id;
    }
    
    public function getStudentnumber(){
        return $this->studentnumber;
    }
    public function getPassword(){
        return $this->password;
    }
}
?>
