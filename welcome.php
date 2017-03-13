<?php
   include('session.php');
   $selfIDQuery = mysqli_query($conn, "select * from account where email_address = '$user_check'");
   $self_row = mysqli_fetch_array($selfIDQuery);
   if ($self_row['isAdmin'] == 1){
     header("location:admin_welcome.php");
   }
   if (isset($_SESSION['login_user'])){
     $selfID = $self_row['accountID'];
   } else {
     $selfID = "error";
   }

?>
<html>

   <head>
      <title>Welcome </title>
      <?php require_once('head.php');?>
    </head>

   <body>
     <?php require_once('common_navbar.html');?>
     <script>
       $("#profile_header").addClass("active");
     </script>
     <div class="container">    
      <h1>Welcome <?php echo $login_session; ?></h1>
        <h2 class = "btn btn-info"> <?php echo "<a href = user_profile_update.php?user_id=". $selfID. ">Edit</a>";?></h2>
        <div>
        <div class="row">
                
                 <div class="col-sm-3">
        <img src="image5.png" style="width:70%" alt="Image">
        </div></div>
          <table border="0" width="300" class="demo-table">
        		<tr><td>Email Address</td>
        		<td><?php echo $self_row['email_address']; ?></td>
        		</tr>
        		<tr><td>Name</td>
        		<td><?php  echo $self_row['name']; ?></td>
        		</tr>
            <tr><td>Age</td>
        		<td><?php  echo $self_row['age']; ?></td>
        		</tr>
            <tr><td>Country</td>
            <td><?php  echo $self_row['country']; ?></td>
            </tr>
            <tr><td>City</td>
            <td><?php echo $self_row['city']; ?></td>
            </tr>
            <tr><td>Introduction</td>
            <td><p><?php  echo $self_row['self_introduction'];?></p></td>
            </tr>
            <tr><td>Privacy Setting</td>
            <td><?php echo $self_row['privacy_setting']?>
        		</td>
            </tr>
        	</table>
        </div>
        <h2 class = "btn btn-info"><a href = "logout.php">Sign Out</a></h2>
        <br><br>
      <?php require_once('common_footer.html');?>
      </div>
   </body>

</html>
