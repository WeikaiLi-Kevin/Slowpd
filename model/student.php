<?php
class StudentsList{
		private $_id;
		private $studentName;
		private $studentnumber;
        private $emailAddress;
        private $password;
        private $hashemail;
 		
		function __construct($_id, $studentName, $studentnumber, $emailAddress, $password){
			$this->setId($_id);
			$this->setStudentName($studentName);
			$this->setstudentnumber($studentnumber);
            $this->setEmailAddress($emailAddress);
            $this->setpassword($password);
		}
     		
		public function getId(){
			return $this->_id;
		}
		
		public function setId($_id){
			$this->_id = $_id;
		}
		
		public function getStudentName(){
			return $this->studentName;
		}
		
		public function setStudentName($studentName){
			$this->studentName = $studentName;
		}
		
		public function getStudentnumber(){
			return $this->studentnumber;
		}
        
		public function setStudentnumber($studentnumber){
			$this->studentnumber = $studentnumber;
		}
		
		public function getEmailAddress(){
			return $this->emailAddress;
		}
        
		public function setEmailAddress($emailAddress){
			$this->emailAddress = $emailAddress;
		}
		
		public function getPassword(){
			return $this->password;
		}
        
		public function setPassword($password){
			$this->password = $password;
		}
    
        public function setHashemail($emailAddress){
            $this->hashemail = password_hash($emailAddress, PASSWORD_DEFAULT);            
        }
    
        public function getHashemail(){
            return $this->hashemail;
        }
    
        public function hashemail()
        {
            $this->setHashemail($this->emailAddress);
            $this->emailAddress=$this->getHashemail();
        }
	}
?>