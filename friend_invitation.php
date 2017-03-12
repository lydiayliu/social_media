<?php
include("dbconfig.php");
include("friending_functions.php");
ini_set('display_errors', 1);
#require_once('common_navbar.html');
session_start();
if (isset($_SESSION['login_user'])) {
    $user_email = $_SESSION['login_user'];
} else {
    $user_email = "error";
}
$user_accountID = mysqli_fetch_assoc(search_by_email($user_email,$conn))['accountID'];
$friend_list = load_friend_invitation($user_accountID, $conn);
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

        <!--the common nav bar-->
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
                        <li id="profile_header"><a href="welcome.php">Profile</a></li>
                        <li id="friendList_header"><a href="FriendList.php">Friend list</a></li>
                        <li id="friendInvitation_header"><a href="friend_invitation.php">Friend invitation</a></li>
                        <li id="selectedFriends_header"><a href="select_friends.php">Create friend circle</a></li>
                        <li id="chatRoom_header"><a href="chat_room.php">Chat room</a></li>
                        <li id="chatRoom_header"><a href="blog.php">Blog</a></li>
                        <li id="chatRoom_header"><a href="allCollections.php">Photo Collections</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span> Log out</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">    
            <div class="row">
                <div class="col-sm-3">
                    <h3>    Friend invitation</h3>
                    <?php
                    if ($friend_list->num_rows == 0) {
                        echo "No friend invitations yet. <br>Go get some friends!";
                    } else {
                        while ($row = mysqli_fetch_assoc($friend_list)) {
                            $friend_accountID = $row['accountID'];
                            $privacy_setting = check_privacy_status($friend_accountID, $conn);
                            if (!check_inv_status($user_accountID, $friend_accountID, $conn)) {
                                ?>
                                <img src="image5.png" class="img-responsive" style="width:80%" alt="Image">
                                <p><?php
                                    if ($privacy_setting == "public") {
                                        echo "<br>Name: " . $row['name'] . "<br>Email address: " . $row['email_address'] . "<br>Age: " . $row['age'] . "<br>Self_introduction: " . $row['self_introduction'] . "<br>City: " . $row['city'] . "<br>Country: " . $row['country'];
                                    } else {
                                        echo "<br>Name: " . $row['name'] . "<br>City: " . $row['city'];
                                    }
                                    ?></p>
                                <form action="">
                                    <input type="submit" class = "btn btn-warning" name="select" value="Receive" />
                                    <input name="a"  type="hidden" id="a" value= "<?php echo $row['accountID']; ?>" />
                                </form>
                                <form action="">
                                    <input type="submit" class = "btn" name="select" value="Reject" />
                                    <input name="b"  type="hidden" id="b" value= "<?php echo $row['accountID']; ?>" />
                                </form>

                                <br>

                                <?php
                                if (isset($_REQUEST["a"])) {
                                    $a = $_REQUEST["a"];
                                    if ($a == $row['accountID']) {
                                        accept_invitation($user_accountID, $friend_accountID, $conn);
                                        echo "<script>location.href='FriendList.php'</script>";
                                    }
                                }
                                if (isset($_REQUEST["b"])) {
                                    $b = $_REQUEST["b"];

                                    if ($b == $row['accountID']) {
                                        reject_invitation($user_accountID, $friend_accountID, $conn);
                                        echo "<script>location.href='friend_invitation.php'</script>";
                                    }
                                }
                            }
                        }
                    }
                    ?>

                </div>


                <div class="col-sm-3">
                    <h3>    Invitations sent</h3>
                    <?php
                    $sent_friend_invitation = load_sent_friend_invitation($user_accountID, $conn);
                    if ($sent_friend_invitation->num_rows == 0) {
                        echo "No sent invitations yet. <br>Go get some friends!";
                    } else {
                        while ($row = mysqli_fetch_assoc($sent_friend_invitation)) {
                            $friend_accountID = $row['accountID'];
                            $privacy_setting = check_privacy_status($friend_accountID, $conn);
                            $status = check_inv_status($friend_accountID, $user_accountID, $conn);
                            ?>
                            <img src="image5.png" class="img-responsive" style="width:80%" alt="Image">
                            <p><?php
                                if ($privacy_setting == "public") {
                                    echo "<br>Name: " . $row['name'] . "<br>Email address: " . $row['email_address'] . "<br>Age: " . $row['age'] . "<br>Self_introduction: " . $row['self_introduction'] . "<br>City: " . $row['city'] . "<br>Country: " . $row['country'];
                                } else {
                                    echo "<br>Name: " . $row['name'] . "<br>City: " . $row['city'];
                                }
                                if (!$status) {
                                    echo "<br>Status: pending<br><br>";
                                } else {
                                    ?></p>
                                <form action="">
                                    <input type="submit" class = "btn btn-warning" name="select" value="Okay" />
                                    <input name="a"  type="hidden" id="a" value= "<?php echo $row['accountID']; ?>" />
                                </form>
                                <br>

                                <?php
                                $a = $_REQUEST["a"];
                                if ($a == $row['accountID']) {
                                    delete_invitation($user_accountID, $friend_accountID, $conn);
                                    echo "<script>location.href='friend_invitation.php'</script>";
                                }
                            }
                        }
                    }
                    ?>

                </div>


                <div class="col-sm-2">

                </div>

                <div class="col-sm-4">
                    <br><br><br><br>
                    <h4><span style="color:#045FB4">Recommend friends according to</span></h4><br>
                    <p><b><span style="color:#424242"> - your location and age:</span></b></p>
         <?php
         if (isset($_POST["c"])){
            $recommend_friend_list = recommend_friend($user_accountID,$conn);
            unset($_POST["c"]); ?>
            <form action="friend_invitation.php" method="post" style="text-align: right;">
                 <input type="submit" class = "btn btn-primary" name="select" value="Hide" />
                 <input name="" type="hidden" id="" value="" />
           </form>
            <?php

       if ($recommend_friend_list->num_rows < 2) {
        ?> <img src="image1.jpeg" class="img-responsive" style="width:80%" alt="Image"> <?php
        echo "Sorry, no users found in the same country.";
      } else {
        while ($row = mysqli_fetch_assoc($recommend_friend_list)){
            $friend_accountID = $row['accountID'];
            $isFriend = false;
            $f_friend_list = load_friend_list($user_accountID, $conn);
            while ($f_row = mysqli_fetch_assoc($f_friend_list)){
              if ($friend_accountID==$f_row['accountID']){
                $isFriend = true;
              }
            }
            if (($row['accountID']!=$user_accountID)&&(!$isFriend)){
            $privacy_setting = check_privacy_status($friend_accountID,$conn);

             ?>
            <img src="image5.png" class="img-responsive" style="width:50%" alt="Image">
            <p>
            <?php    
              $isInvited = false;
              $sent_friend_invitation = load_sent_friend_invitation($user_accountID, $conn);
              while ($i_row = mysqli_fetch_assoc($sent_friend_invitation)){
                if ($row['accountID']==$i_row['accountID']){
                $isInvited = true;}
              }

              $hasBeenInvited = false;
              $sent_friend_invitation = load_sent_friend_invitation($friend_accountID, $conn);
              while ($j_row = mysqli_fetch_assoc($sent_friend_invitation)){
                if ($user_accountID==$j_row['accountID']){
                $hasBeenInvited = true;}
              }

              if ($privacy_setting == "public") {
              echo "<br>Name: ".$row['name']."<br>Email address: ".$row['email_address']."<br>Age: ".$row['age']."<br>Self_introduction: ".$row['self_introduction']."<br>City: ".$row['city']."<br>Country: ".$row['country'];
              ?></p>
              <ul class="nav navbar-nav">
               <li><a href="allCollections.php?accountID=<?php echo $row['accountID']?>">Photos</a></li>
               <li class="active"><a href="blog.php?accountID=<?php echo $row['accountID']?>">Blog</a></li>
              </ul>
            <?php if ($isInvited||$hasBeenInvited){ 
              echo '<b>Invitation has been sent. </b><a href="friend_invitation.php">Go and check.</a><br><br>';
             } else { ?>
             <form action="friending_backend.php" method="post">
               <input type="submit" class = "btn btn-warning" name="select" value="Send invitation" />
               <input name="a" type="hidden" id="a" value="<?php
          echo $row['accountID'];?>" />
             </form>
             <br>

           <?php
              }} else {
                echo "<br>Name: ".$row['name']."<br>City: ".$row['city'];
           ?>

             <ul class="nav navbar-nav">
              <li><a href="allCollections.php?accountID=<?php echo $row['accountID']?>">Photos</a></li>
             </ul>
          <?php if ($isInvited||$hasBeenInvited){ 
            echo '<b>Invitation has been sent. </b><a href="friend_invitation.php">Go and check.</a><br><br>';
           } else { ?>
             <form action="friending_backend.php" method="post">
               <input type="submit" class = "btn btn-warning" name="select" value="Send invitation" />
               <input name="a" type="hidden" id="a" value="<?php
          echo $row['accountID'];?>" />
             </form>
           <?php }}
          }}} 

         } else { ?>
            <form action="friend_invitation.php" method="post" style="text-align: right;">
                    <input type="submit" class = "btn btn-primary" name="select" value="Go" />
                     <input name="c" type="hidden" id="c" value="" />
                     </form>
          <?php } ?>

          <br><br>
         <p><b><span style="color:#424242"> - friends of your friends:</span></b></p>

        <?php
         if (isset($_POST["d"])){
            $mutual_friends_reco_friend_list = mutual_friends_reco($user_accountID,$conn);
            unset($_POST["d"]); ?>
            <form action="friend_invitation.php" method="post" style="text-align: right;">
                <input type="submit" class = "btn btn-primary" name="select" value="Hide" />
                <input name="" type="hidden" id="" value="" />
           </form>
           <?php
            if ($mutual_friends_reco_friend_list->num_rows == 0) {
        ?>
        <img src="image1.jpeg" class="img-responsive" style="width:80%" alt="Image"> <?php
        echo "Sorry, no new friends from your friends";
      } else {
        while ($row = mysqli_fetch_assoc($mutual_friends_reco_friend_list)){
            $friend_accountID = $row['accountID'];
            $isFriend = false;
            $f_friend_list = load_friend_list($user_accountID, $conn);
            while ($f_row = mysqli_fetch_assoc($f_friend_list)){
              if ($friend_accountID==$f_row['accountID']){
                $isFriend = true;
              }
            }
            if (!$isFriend){
            $privacy_setting = check_privacy_status($friend_accountID,$conn);
             ?>
            <img src="image5.png" class="img-responsive" style="width:50%" alt="Image">
            <p>
            <?php    
              $isInvited = false;
              $hi = " noo:";
              $sent_friend_invitation = load_sent_friend_invitation($user_accountID, $conn);
              while ($i_row = mysqli_fetch_assoc($sent_friend_invitation)){
                if ($friend_accountID==$i_row['accountID']){
                $isInvited = true;}
              }

              $hasBeenInvited = false;
              $sent_friend_invitation = load_sent_friend_invitation($friend_accountID, $conn);
              while ($j_row = mysqli_fetch_assoc($sent_friend_invitation)){
                if ($user_accountID==$j_row['accountID']){
                $hasBeenInvited = true;}
              }

              if ($privacy_setting == "public") {
              echo "<br>Name: ".$row['name']."<br>Email address: ".$row['email_address']."<br>Age: ".$row['age']."<br>Self_introduction: ".$row['self_introduction']."<br>City: ".$row['city']."<br>Country: ".$row['country'].$hi.$i_row['name'];
              ?></p>
              <ul class="nav navbar-nav">
               <li><a href="allCollections.php?accountID=<?php echo $row['accountID']?>">Photos</a></li>
               <li class="active"><a href="blog.php?accountID=<?php echo $row['accountID']?>">Blog</a></li>
              </ul>
            <?php if ($isInvited||$hasBeenInvited){ 
              echo '<b>Invitation has been sent. </b><a href="friend_invitation.php">Go and check.</a><br>';
             } else {  ?>
             <form action="friending_backend.php" method="post">
               <input type="submit" class = "btn btn-warning" name="select" value="Send invitation" />
               <input name="a" type="hidden" id="a" value="<?php
          echo $row['accountID'];?>" />
             </form>
             <br>

           <?php
              }} else {
                echo "<br>Name: ".$row['name']."<br>City: ".$row['city'];
           ?>

             <ul class="nav navbar-nav">
              <li><a href="allCollections.php?accountID=<?php echo $row['accountID']?>">Photos</a></li>
             </ul>
          <?php 
          if ($isInvited||$hasBeenInvited){ 
            echo '<b>Invitation has been sent. </b><a href="friend_invitation.php">Go and check.</a><br><br>';
           } else { ?>
             <form action="friending_backend.php" method="post">
               <input type="submit" class = "btn btn-warning" name="select" value="Send invitation" />
               <input name="a" type="hidden" id="a" value="<?php
          echo $row['accountID'];?>" />
             </form>
           <?php }}
          }}} 
        } else { ?>
           <form action="friend_invitation.php" method="post" style="text-align: right;">
                <input type="submit" class = "btn btn-primary" name="select" value="Go" />
                <input name="d" type="hidden" id="d" value="" />
           </form>
             <br><br>
        <?php } ?>
         <br>

                </div>
                <hr>

            </div></div>
            <br>


    

        <?php require_once('common_footer.html');?>



    </body>

</html>





