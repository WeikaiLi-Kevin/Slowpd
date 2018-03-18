<?php
require_once('abstractDAO.php');
require_once('./model/student.php');

class studentDAO extends abstractDAO {
        
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }
    
    public function getContacts(){
        //The query method returns a mysqli_result object
        $result = $this->mysqli->query('SELECT * FROM studentList');
        $mailingList = Array();
      
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){              
                $mailingList = new StudentsList($row['id'], $row['studentName'], $row['studentNumber'], $row['emailAddress'], $row['password']);
                $mailingLists[] = $mailingList;
            }
            $result->free();
            return $mailingLists;
        }
        $result->free();
        return false;
    }
       
    public function getContact($Id){
        $query = 'SELECT * FROM studentList WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $mailingList =  new MailingList($temp['id'], $temp['studentName'], $temp['studentnumber'], $temp['emailAddress'], $temp['password']);
            $result->free();
            return $mailingList;
        }
        $result->free();
        return false;
    }

    public function addContact($contact){
        if(!$this->mysqli->connect_errno){
            
            $query = 'INSERT INTO studentList (studentName, studentnumber, emailAddress, password) VALUES (?,?,?,?)';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('ssss',                     
                    $contact->getStudentName(), 
                    $contact->getstudentnumber(), 
                    $contact->getEmailAddress(), 
                   
                    $contact->getPassword());
             
            $stmt->execute();          
            if($stmt->error){
                return $stmt->error;
            } else {
                return $contact->getStudentName() . ' ' . $contact->getStudentnumber() . ' added successfully!';
            }
        } else {
            return 'Could not connect to Database.';
        }
    }
    
    public function deleteContact($Id){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM studentList WHERE _id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $Id);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function editContact($Id, $studentName, $studentnumber, $emailAddress, $referrer){
        if(!$this->mysqli->connect_errno){
            $query = 'UPDATE studentList SET studentName = ?, studentnumber = ?, emailAddress = ?, referrer = ? WHERE _id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('ssssi', $studentName, $studentnumber, $emailAddress, $referrer, $Id);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return $stmt->affected_rows;
            }
        } else {
            return false;
        }
    }
}

?>
