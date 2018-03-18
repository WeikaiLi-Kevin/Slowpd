<?php require_once('./dao/customerDAO.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Professor Signup</title>
    <?php
        include 'header.php';
    ?>   
    <body>
        <?php

        try{
            $customerDAO = new customerDAO();
            //Tracks errors with the form fields
            $hasError = false;
            //Array for our error messages
            $errorMessages = Array();

            //Ensure all three values are set.
            //They will only be set when the form is submitted.
            //We only want the code that adds an employee to 
            //the database to run if the form has been submitted.
            
                if(isset($_POST['customerName']) ||
                    isset($_POST['phoneNumber']) || 
                    isset($_POST['emailAddress'])|| 
                    isset($_POST['referral'])){

                    //We know they are set, so let's check for values

                    if($_POST['customerName'] == ""){
                        $errorMessages['NameError'] = "enter a name.";
                        $hasError = true;
                    }
					

                    if($_POST['phoneNumber'] == ""){
                        $hasError = true;
                        $errorMessages['PhoneError'] = 'enter a phone number.';
                    }else{
                        $phonepattern = '/^[0-9]{9}$/';

                        if (preg_match($phonepattern, $_POST['phoneNumber']) === 1) {
                        // phone is valid
                        }else{
                            $errorMessages['PhoneError'] = "enter a valid phone number.";
                            $hasError = true;                        
                        }
					 }	
					if($_POST['password'] == ""){
                        $errorMessages['PasswordError'] = "enter a password.";
                        $hasError = true;
                    }
                   

                    if($_POST['emailAddress'] == ""){
                        $errorMessages['EmailError'] = "enter a email.";
                        $hasError = true;
                    }else{
                        $pattern = '/[[:<:]][!#$%&\'*+.\/0-9=?_`a-z{|}~^-]++@[.0-9a-z-]+\.[a-z]{2,63}+[[:>:]]/i';

                        if (preg_match($pattern, $_POST['emailAddress']) === 1) {
                            // emailaddress is valid
                            $contacts = $customerDAO->getContacts();
                            if($contacts){
                                foreach($contacts as $contact){
                                    if ($contact->getEmailAddress() == $_POST['emailAddress'])
                                    {
                                        $errorMessages['EmailError'] = "email address already exist, try another.";
                                        $hasError = true;                        

                                    }
                                }
                            }
                        }else{
                            $errorMessages['EmailError'] = "enter a valid email address.";
                            $hasError = true;                        
                        }
                    }
                    if(!array_key_exists("referral",$_POST)){                    
                        $errorMessages['ReferralError'] = "choose a referral.";
                        $hasError = true;
                    }

                    if(!$hasError){
                        $id = 1;
                        $mailinglist = new ProfessorsList($id,$_POST['customerName'],$_POST['phoneNumber'], $_POST['emailAddress'], $_POST['referral']);
                        $mailinglist->hashemail();
                        $addSuccess = $customerDAO->addContact($mailinglist);
                        echo '<h3>' . $addSuccess . '</h3>';

                    }
                }  
               
                if(isset($_GET['deleted'])){
                    if($_GET['deleted'] == true){
                        echo '<h3>Contact Deleted</h3>';
                    }
                }                
            
        ?>
        <div id="wrapper">            
            <nav>
                <div id="menuItems">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="menu.php">Professor Login</a></li>		 
                    </ul>
                </div>
            </nav>
            <div id="content" class="clearfix">
                <aside>
                        <h2>Mailing Address</h2>
                        <h3>1385 Woodroffe Ave<br>
                            Ottawa, ON K4C1A4</h3>
                        <h2>Phone Number</h2>
                        <h3>(613)727-4723</h3>
                        <h2>Fax Number</h2>
                        <h3>(613)555-1212</h3>
                        <h2>Email Address</h2>
                        <h3>info@wpeatery.com</h3>
                </aside>
                <div class="main">
                    <h1>Sign up for our newsletter</h1>
                    <p>Please fill out the following form to be kept up to date with news!</p>
                    <form name="frmNewsletter" id="frmNewsletter" method="post" action="ProfessorSignup.php"  enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Name:</td>
                                <td><input type="text" name="customerName" id="customerName" size='40' </td>                                      
                                <?php 
                                    if(isset($errorMessages['NameError'])){
                                    echo '<span style=\'color:red\'>' . $errorMessages['NameError'] . '</span>';
                                    }
									?>                                   								
                            </tr>
                            <tr>
                                <td>Professor ID:</td>
                                <td><input type="text" name="phoneNumber" id="phoneNumber" size='40' </td>
                                                                                                                             
                                <?php 
                                    if(isset($errorMessages['PhoneError'])){
                                        echo '<span style=\'color:red\'>' . $errorMessages['PhoneError'] . '</span>';
                                    }
                                    ?>
                            </tr>
                            <tr>
                                <td>Email Address:</td>
                                <td><input type="email" name="emailAddress" id="emailAddress" size='40' </td>                                                                                     
                                <?php                                     
                                    if(isset($errorMessages['EmailError'])){
                                    echo '<span style=\'color:red\'>' . $errorMessages['EmailError'] . '</span>';
                                    }
                                    ?>
                            </tr>
							<tr>
								<td>Password:</td>
								<td><input type="password" name="password" id="password" size='20'  </td>
								<?php 
                                    if(isset($errorMessages['PasswordError'])){
                                    echo '<span style=\'color:red\'>' . $errorMessages['PasswordError'] . '</span>';
                                    }
									?> 
							</tr>
                            <tr>
                                <td>Prefer by email <br> or phone?</td>
                                <td>Email<input type="radio" name="referral" id="referralNewspaper" value="email">
                                    Phone<input type="radio" name='referral' id='referralRadio' value='phone'>
                                     
                                    <?php 
                                        if(!isset($_POST['upload'])){
                                            if(!array_key_exists("referral",$_POST)){
                                            echo '<span style=\'color:red\'>' . "Please choose your prefer." . '</span>';
                                            }
                                        }
                                        ?>
								</td>
                            </tr>                           
                            <tr>
                                <td colspan='2'><input type='submit' name='btnSubmit' id='btnSubmit' value='Sign up!'>&nbsp;&nbsp;<input type='reset' name="btnReset" id="btnReset" value="Reset Form"></td>
                            </tr>
                        </table>
                    </form>
                </div><!-- End Main -->
            </div><!-- End Content -->
            <?php include 'footer.php'; ?>
        </div><!-- End Wrapper -->
        <?php
        
        }catch(Exception $e){           
            echo '<h3>Error on page.</h3>';
            echo '<p>' . $e->getMessage() . '</p>';            
        }
        ?>        
    </body>
</html>
