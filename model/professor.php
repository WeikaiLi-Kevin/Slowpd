<?php
class ProfessorsList{
		private $_id;
		private $customerName;
		private $phoneNumber;
        private $emailAddress;
        private $referrer;
        private $hashemail;
		
		function __construct($_id, $customerName, $phoneNumber, $emailAddress, $referrer){
			$this->setId($_id);
			$this->setCustomerName($customerName);
			$this->setPhoneNumber($phoneNumber);
            $this->setEmailAddress($emailAddress);
            $this->setReferrer($referrer);
		}
     		
		public function getId(){
			return $this->_id;
		}
		
		public function setId($_id){
			$this->_id = $_id;
		}
		
		public function getCustomerName(){
			return $this->customerName;
		}
		
		public function setCustomerName($customerName){
			$this->customerName = $customerName;
		}
		
		public function getPhoneNumber(){
			return $this->phoneNumber;
		}
        
		public function setPhoneNumber($phoneNumber){
			$this->phoneNumber = $phoneNumber;
		}
		
		public function getEmailAddress(){
			return $this->emailAddress;
		}
        
		public function setEmailAddress($emailAddress){
			$this->emailAddress = $emailAddress;
		}
		
		public function getReferrer(){
			return $this->referrer;
		}
        
		public function setReferrer($referrer){
			$this->referrer = $referrer;
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