<!DOCTYPE html>
<html>
    <head>
        <title>Appointment Booking System </title>
    <?php
    include 'header.php';
    ?>
	
	<?php
 

class menuItem{
    private $itemName;
    private $description;
    private $price;
    
    public function menuItem($a1,$a2,$a3)
    {
        $this->itemName=$a1;
        $this->description=$a2;
        $this->price=$a3;
    }
    
    public function getItemName(){
        return $this->itemName;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function setItemName($a1)
    {
        $this->itemName=$a1;
    }
    
    public function setDescription($a1)
    {
        $this->description=$a1;
    }
    
    public function setPrice($a1)
    {
        $this->price=$a1;
    }
}
$daysArray = Array(
					0 => 'Sunday',
					1 => 'Monday',
					2 => 'Tuesday',
					3 => 'Wednsday',
					4 => 'Thursday',
					5 => 'Friday',
					6 => 'Saturday',
					); 
?>
 <?php
                    $todaymenuitem1 = new menuItem("Algonquin Collee",
					                                "Freshly made all-beef patty served up with homefries", "$14");
                    $todaymenuitem2 = new menuItem("WP Kebobs",
					                               "Tender cuts of beef and chicken, served with your choice of side","$17");
                ?>

    <body>
        <div id="wrapper"> 
            
            <nav>
                <div id="menuItems">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="studentLogin.php">StudentLogin</a></li>
                        <li><a href="professorLogin.php">ProfessorLogin</a></li>						 
                        <li><a href="professors_list.php">Admin</a></li>
                    </ul>
                </div>
            </nav>
               
            <div id="content" class="clearfix">
                <aside>
                         
						<h2> Today is <?php  echo $daysArray[date("w")];?></h2>
                        <hr>
                        <img src="images/burger.jpg" alt="Burger" title="Monday's Special - Burger">
                       <?php   echo "<h3>". $todaymenuitem1->getItemName()."  </h3>"   ?>
                        
                </aside>

                <div class="main">
                    <h1>Welcome</h1>
                    <img src="images/dining_room.jpg" alt="Dining Room" title="The WP Eatery Dining Room" class="content_pic">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt  </p>
                    <h2>Book your Appointment!</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do  </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, s   </p>
                </div><!-- End Main -->
            </div><!-- End Content -->
            
        </div><!-- End Wrapper -->
    </body>
</html>
<?php  include 'footer.php'; ?>