 <?php  include 'header.php'; ?>   
<!DOCTYPE html>
<html>
    <head>
        <title>Stuent Register</title> 
    <body>
        <div id="wrapper">            
            <nav>
                <div id="menuItems">
                    <ul>
                        <li><a href="index.php">Home</a></li>
						<li><a href="index.php">ProfessorLogin</a></li>                                       
                    </ul>
                </div>
            </nav>
            <div id="content" class="clearfix">
                <aside>
                        <h2>Algonquin College</h2>
                        <h3>1385 Woodroffe Ave<br>
                            Ottawa, ON K4C1A4</h3>
                        <h2>Phone Number</h2>
                        <h3>(613)727-4723</h3>
                        <h2>Fax Number</h2>
                        <h3>(613)555-1212</h3>
                        <h2>Email Address</h2>
                        <h3>info@algonquinlive.com</h3>
                </aside>
                <div class="main">
                    <h1>Register in Appointment Booking System</h1>
                    <p>Please fill out the following form to be kept up to date with our Appointment Booking System!</p>
                    <form name="frmNewsletter" id="frmNewsletter" method="post" action="ProfessorSignup.php" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Name:</td>
                                <td><input type="text" name="customerName" id="customerName" size='40' value="">
                                </td>
                            </tr>
                            <tr>
                                <td>Professor ID:</td>
                                <td><input type="text" name="phoneNumber" id="phoneNumber" size='40' value="">
                                </td>
                            </tr>
                            <tr>
                                <td>Email Address:</td>
                                <td><input type="email" name="emailAddress" id="emailAddress" size='40' value="">
                                </td>
                            </tr>
							<tr>
								<td>Password:</td>
								<td><input type="password" name="password" id="password" size='20'  </td>								
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
        
    </body>
</html>
