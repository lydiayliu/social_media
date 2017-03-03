<?php
	include("dbconfig.php");
  include("friending_functions.php");
  	session_start();
  	if (isset($_SESSION['login_user'])){
      $user_email = $_SESSION['login_user'];
    } else {
      $user_email = "error";
    }
  	$load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
  	$user_accountID = mysqli_fetch_assoc(mysqli_query($conn,$load_accountID))['accountID'];
    $friend_name = mysqli_real_escape_string($conn, $_POST["name"]);
  	#$friend_ID = mysqli_fetch_assoc(search_for_friends($friend_name, $conn))['accountID'];
	  $friend_list = search_for_friends($friend_name, $conn);
    
  
  
  #$name = mysqli_real_escape_string($dbconn, $_POST["name"]);
  

  
 		
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Social Media</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

    .carousel-inner img {
      width: 100%; /* Set width to 100% */
      min-height: 200px;
    }

    /* Hide the carousel text when the screen is less than 600 pixels wide */
    @media (max-width: 600px) {
      .carousel-caption {
        display: none; 
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="#">Profile</a></li>
        <li class="active"><a href="#">friends found</a></li>
        <li><a href="#">Friend invitation</a></li>
        <li><a href="#">Friend circle</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">    
  <h3>    Friend list</h3>
  <br>
  <div class="row">
  <div class="col-sm-3">
  	<?php if ($friend_list->num_rows == 0) {
				echo "no result found";
			} else {
				while ($row = mysqli_fetch_assoc($friend_list)){?>
					
      				<img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
      				<p><?php
					echo "<br>Name: ".$row['name']."<br>Email address: ".$row['email_address']."<br>Age: ".$row['age'];
					 ?></p>
					<form action="">
    				<input type="submit" class = "btn btn-warning" name="select" value="Add" />
    				<input name="a" type="hidden" id="a" value="a" />
					</form>
					<br>

					<?php
						$a=$_REQUEST["a"];
						if ($a=="a"){
							$friend_email = $row['email_address'];
							$load_friendID = "SELECT accountID FROM Account WHERE email_address = '$friend_email'";
  							$friend_accountID = mysqli_fetch_assoc(mysqli_query($conn,$load_friendID))['accountID'];
							$query_add_friend = "INSERT INTO Friendship (friend1ID, friend2ID) VALUES ('$user_accountID','$friend_accountID')";
							if (mysqli_query($conn,$query_add_friend)){
								echo "<script>location.href='FriendList.php'</script>";
							}
						}
					?>
					 
    			<p><?php  }} ?></p>
				
 </div>
    
    
    <div class="col-sm-5">
    <?php for ($x=0; $x<$friend_list->num_rows; $x++){?>
    <br><br><br><br><br><br><br><br>
      
    <?php } ?>
    </div>

    <div class="col-sm-4">
     <h4>Searching for a friend:</h4>
     <br>
  
    
 <form action="">
 <div class="row">
  <div class="col-sm-6">
  <p>Name:
  <input type="text" name = "name" class="form-control" placeholder="Jacky" aria-describedby="basic-addon1" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>"> </p>
  </div> 

  <div class="col-sm-6">
  <p>Age: 
  <input type="number" class="form-control" placeholder="25" aria-describedby="basic-addon1" value="<?php if(isset($_POST['age'])) echo $_POST['age']; ?>"> </p>
  </div></div>
  <br>

<div class="row">
  <div class="col-sm-6">
  <p>City:
  <input type="text" class="form-control" placeholder="london" aria-describedby="basic-addon1" value="<?php if(isset($_POST['city'])) echo $_POST['city']; ?>"> </p>
  </div>

  <div class="col-sm-6">
  <p>Country:
  <input type="text" class="form-control" placeholder="U.K." aria-describedby="basic-addon1" value="<?php if(isset($_POST['country'])) echo $_POST['country']; ?>"> </p>
  </div>
</div>

  <br>
  <div class="row">
  <div class="col-sm-12">
  <p>Email address:
  <input type="text" class="form-control" placeholder="xxx@fake.com" aria-describedby="basic-addon1" value="<?php if(isset($_POST['email_address'])) echo $_POST['email_address']; ?>"> </p>
  </div>
  </div>
  <br>

  <div class="row">
    <div class="col-sm-6"></div>

  <div class="col-sm-2">
  <input type="submit" class = "btn btn-info" name="select" value="Search" />
  </div>
  </div>
  
   </form>
 

  </div>
  <hr>

</div>


<br>
<footer class="container-fluid">
  <h5>Database Group 10
  </h5>
</footer>



</body>

</html>



