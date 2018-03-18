<?php 
require_once('AdminUser.php');
session_start();
session_regenerate_id(false);

if(isset($_SESSION['websiteUser'])){
    if(!$_SESSION['websiteUser']->isAuthenticated()){
       header('Location:Adminlogin.php'); 
    }
} else {
    header('Location:Adminlogin.php');
}
?>

<?php require_once('./dao/customerDAO.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Professors' Lists</title>
    <?php include 'header.php';  ?>   
    <body>
        <div id="wrapper">
             
            <nav>
                <div id="menuItems">
                    <ul>
                        <li><a href="index.php">Home</a></li>                                		 
                        <li><a href="professors_list.php">Admin</a></li>
                    </ul>
                </div>
            </nav>
            <div id="content" class="clearfix">                        
                
    <?php
        if($_SESSION['websiteUser']->isAuthenticated())
        {
            echo '<p> Session AdminID = '.$_SESSION['websiteUser']->getAdminID().'</p>';           
            echo '<p> Last Login Date = '.$_SESSION['websiteUser']->getLastlogin().'</p>';
        }
        try{
            $customerDAO = new customerDAO();
            $hasError = false;
            $errorMessages = Array();

            $contacts = $customerDAO->getContacts();
            if($contacts){
                echo '<table border=\'1\'>';
                echo '<tr><th>Professor Name</th><th>Phone Number</th><th>EMail</th><th>Referrer</th></tr>';
                foreach($contacts as $contact){
                    echo '<tr>';               
                    echo '<td>' . $contact->getCustomerName() . '</td>';
                    echo '<td>' . $contact->getPhoneNumber() . '</td>';
                    echo '<td>' . $contact->getEmailAddress() . '</td>';
                    echo '<td>' . $contact->getReferrer() . '</td>';
                    echo '</tr>';
                }
            }
        
        }catch(Exception $e){
            echo '<h3>Error on page.</h3>';
            echo '<p>' . $e->getMessage() . '</p>';            
        }
?>
        </table> 
		<form name="logout" id="logout" method="post" action="logout.php">
        <table>
            <tr>
            <input type="submit" name="submit" id="submit" value="Logout">
            </tr>
        </table>
        </form>                
		
            </div><!-- End Content -->           
        </div><!-- End Wrapper -->
    </body>
</html> 
<?php include 'footer.php'; ?>