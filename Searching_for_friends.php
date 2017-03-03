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
$friend_list = load_friend_list($user_accountID, $conn);

if (isset($_POST["name"])){
      $friend_name = mysqli_real_escape_string($conn, $_POST["name"]);
      $search_friend_list = search_by_name($friend_name, $conn);
} else if (isset($_POST["age"])){
      $friend_age = mysqli_real_escape_string($conn, $_POST["age"]);
      $search_friend_list = search_by_age($friend_age, $conn);
} else if (isset($_POST["city"])){
      $friend_city = mysqli_real_escape_string($conn, $_POST["city"]);
      $search_friend_list = search_by_city($friend_city, $conn);
} else if (isset($_POST["country"])){
      $friend_country = mysqli_real_escape_string($conn, $_POST["country"]);
      $search_friend_list = search_by_country($friend_country, $conn);
} else if (isset($_POST["email"])){
      $friend_email = mysqli_real_escape_string($conn, $_POST["email"]);
      $search_friend_list = search_by_email($friend_email, $conn);
} 




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
          <li class="active"><a href="FriendList.php">Friend list</a></li>
          <li><a href="friend_invitation.php">Friend invitation</a></li>
          <li><a href="#">Friend circle</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">    
    <h3>    Friends found:</h3>
    <br>
    <div class="row">
      <div class="col-sm-3">
       <?php 
       if ($search_friend_list->num_rows == 0) {
        echo "no result found";
      } else {
        while ($row = mysqli_fetch_assoc($search_friend_list)){
          if (!($row['accountID']==$user_accountID)){
            $isFriend = false;
            while ($f_row = mysqli_fetch_assoc($friend_list)){
              if ($row['accountID']==$f_row['accountID']){
                $isFriend = true;
              }
            }
            if (!$isFriend){ ?>
            <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
            <p><?php
              echo "<br>Name: ".$row['name']."<br>Email address: ".$row['email_address']."<br>Age: ".$row['age'];
              ?></p>
              <form action="friending_backend.php" method="post">
               <input type="submit" class = "btn btn-warning" name="select" value="Add" />
               <input name="a" type="hidden" id="a" value="<?php
          echo $row['accountID'];?>" />
             </form>
             <br>

             <?php
             
          } else { ?>
           <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
            <p><?php
            echo "<br>Name: ".$row['name']."<br>Email address: ".$row['email_address']."<br>Age: ".$row['age'];
           ?></p>
            <form action="friending_backend.php" method="post">
              <input type="submit" class = "btn btn-warning" name="select" value="Remove" />
              <input name="b" type="hidden" id="b" value="<?php
           echo $row['accountID'];?>" />
            </form>
            <br>

            <?php
            
          
          } }}} ?>

      </div>


      <div class="col-sm-5">

      </div>

      <div class="col-sm-4">
       <h4>Searching for a friend:</h4>
       <br>

       <div class="row">
 <form action="Searching_for_friends.php" method = "post">
  <div class="col-sm-4">
  <p>Name:
  <input type="text" name = "name" class="form-control" placeholder="Jacky" aria-describedby="basic-addon1" > </p>
  </div> 

  <div class="col-sm-2">
  <br>
  <input type="submit" class = "btn btn-info" name="select" value="Go" />
  </div></form>

   <form action="Searching_for_friends.php" method = "post">
  <div class="col-sm-4">
  <p>age:
  <input type="number" name = "age" class="form-control" placeholder="25" aria-describedby="basic-addon1">  </p>
  </div> 

  <div class="col-sm-2">
  <br>
  <input type="submit" class = "btn btn-info" name="select" value="Go" />
  </div></div></form>
  

<div class="row">

 <form action="Searching_for_friends.php" method = "post">
  <div class="col-sm-4">
  <p>City:
  <input type="text" name = "city" class="form-control" placeholder="london" aria-describedby="basic-addon1" > </p>
  </div>
  <div class="col-sm-2">
  <br>
  <input type="submit" class = "btn btn-info" name="select" value="Go" />
  </div></form>

<form action="Searching_for_friends.php" method = "post">
  <div class="col-sm-4">
  <p>Country:
  <input type="text" name = "country" class="form-control" placeholder="U.K." aria-describedby="basic-addon1"> </p>
  </div>
  <div class="col-sm-2">
  <br>
  <input type="submit" class = "btn btn-info" name="select" value="Go" />
</div>
</form>
</div>

  <div class="row">
  <form action="Searching_for_friends.php" method = "post">
  <div class="col-sm-10">
  <p>Email address:
  <input type="text" name = "email" class="form-control" placeholder="xxx@fake.com" aria-describedby="basic-addon1"> </p>
  </div>


  <div class="col-sm-2">
  <br>
  <input type="submit" class = "btn btn-info" name="select" value="Go" />
  </div>
  </form>
  </div>
  
 
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



