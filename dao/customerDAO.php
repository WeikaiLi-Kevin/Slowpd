<?php
require_once('abstractDAO.php');
require_once('./model/professor.php');

class customerDAO extends abstractDAO {
        
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }
    
    public function getContacts(){
        //The query method returns a mysqli_result object
        $result = $this->mysqli->query('SELECT * FROM professorsList');
        $mailingList = Array();
      
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){              
                $mailingList = new ProfessorsList($row['id'], $row['customerName'], $row['phoneNumber'], $row['emailAddress'], $row['referrer']);
                $mailingLists[] = $mailingList;
            }
            $result->free();
            return $mailingLists;
        }
        $result->free();
        return false;
    }
       
    public function getContact($Id){
        $query = 'SELECT * FROM professorsList WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $Id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $mailingList =  new MailingList($temp['id'], $temp['customerName'], $temp['phoneNumber'], $temp['emailAddress'], $temp['referrer']);
            $result->free();
            return $mailingList;
        }
        $result->free();
        return false;
    }

    public function addContact($contact){
        if(!$this->mysqli->connect_errno){
            
            $query = 'INSERT INTO professorsList (customerName, phoneNumber, emailAddress, referrer) VALUES (?,?,?,?)';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('ssss',                     
                    $contact->getCustomerName(), 
                    $contact->getPhoneNumber(), 
                    $contact->getEmailAddress(), 
                   
                    $contact->getReferrer());
             
            $stmt->execute();          
            if($stmt->error){
                return $stmt->error;
            } else {
                return $contact->getCustomerName() . ' ' . $contact->getPhoneNumber() . ' added successfully!';
            }
        } else {
            return 'Could not connect to Database.';
        }
    }
    
    public function deleteContact($Id){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM professorsList WHERE _id = ?';
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
    
    public function editContact($Id, $customerName, $phoneNumber, $emailAddress, $referrer){
        if(!$this->mysqli->connect_errno){
            $query = 'UPDATE professorsList SET customerName = ?, phoneNumber = ?, emailAddress = ?, referrer = ? WHERE _id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('ssssi', $customerName, $phoneNumber, $emailAddress, $referrer, $Id);
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
