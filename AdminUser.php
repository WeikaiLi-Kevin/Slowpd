<?php

class WebsiteUser{    
    protected static $DB_HOST = "127.0.0.1";  
    protected static $DB_USERNAME = "slowpd"; 
    protected static $DB_PASSWORD = "password"; 
    protected static $DB_DATABASE = "slowpd";
    
    private $adminid;
    private $username;
    private $password;
    private $lastlogin;
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
    public function authenticate($username, $password){
        $loginQuery = "SELECT * FROM adminusers WHERE Username = ? AND Password = ?";
        $stmt = $this->mysqli->prepare($loginQuery);
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $this->username = $username;
            $this->password = $password;
            $this->lastlogin = $temp['Lastlogin'];
            $this->adminid = $temp['AdminID'];
            $this->authenticated = true;
            $updatequery = 'UPDATE adminusers SET Lastlogin = now() WHERE AdminID = ?';
            $stmt = $this->mysqli->prepare($updatequery);
            $stmt->bind_param('i', $this->adminid);           
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
    public function getAdminID(){
        return $this->adminid;
    }
    
    public function getUsername(){
        return $this->username;
    }
    public function getLastlogin(){
        return $this->lastlogin;
    }
}
?>
