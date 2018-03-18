  <?php
        include 'header.php';
    ?>   
<?php
    require_once('StudentUsers.php');
    session_start();
    if(isset($_SESSION['websiteUser'])){
        if($_SESSION['websiteUser']->isAuthenticated()){         
        }
    }
    $missingFields = false;
    if(isset($_POST['submit'])){
        if(isset($_POST['username']) && isset($_POST['password'])){
            if($_POST['username'] == "" || $_POST['password'] == ""){
                $missingFields = true;
            } else {               
                $websiteUser = new WebsiteUser();
                if(!$websiteUser->hasDbError()){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $websiteUser->authenticate($username, $password);
                    if($websiteUser->isAuthenticated()){
                        $_SESSION['websiteUser'] = $websiteUser;
                        header('Location:professors_list.php');
                    }
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>   
    <body>
        <div id="wrapper">            
            <nav>
                <div id="menuItems">
                    <ul>
                        <li><a href="index.php">Home</a></li>                     	                      
						<li><a href="Sregister.php">SRegister</a></li>
                    </ul>
                </div>
            </nav>
            <div id="content" class="clearfix">
                       
        <?php           
            if($missingFields){
                echo '<h3 style="color:red;">Please enter both a username and a password</h3>';
            }
                      
            if(isset($websiteUser)){
                if(!$websiteUser->isAuthenticated()){
                    echo '<h3 style="color:red;">Login failed. Please try again.</h3>';
					echo '<h3 style="color:red;"> . $missingFields . </h3>';
                }
            }
        ?>
        
        <form name="login" id="login" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <table>
            <tr>
                <td>Login:</td>
                <td><input type="text" name="username" id="username"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" id="submit" value="Login"></td>
               
            </tr>
        </table>
            <?php // echo '<p>Session ID: ' . session_id() . '</p>';
            ?>
        </form>                
            </div><!-- End Content -->           
        </div><!-- End Wrapper -->
    </body>
</html>
<?php include 'footer.php'; ?>