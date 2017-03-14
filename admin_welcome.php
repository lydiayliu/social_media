<?php
   include('session_admin.php');
   $selfIDQuery = mysqli_query($conn, "select * from account where email_address = '$user_check'");
   $self_row = mysqli_fetch_array($selfIDQuery);
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
     <div class="container">
     <script>
       $("#profile_header").addClass("active");
     </script>
      <h1>Welcome <?php echo $login_session; ?></h1>

      <a href="admin_user_index.php" class="btn btn-primary">View all users</a>
      <a href="admin_annotation_index.php" class="btn btn-primary">View all annotations/comments</a>
      <br/><br/>
      <div class="row">
        <?php echo "<a class = \"btn btn-info\" role =\"button\" href = user_profile_update.php?user_id=". $selfID. ">Edit</a>";?>
        <br/>
      </div>
        <div class="row">
          <table class="table table-striped">
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
        <br/>
        <a href = "logout.php" class = "btn btn-info" role = "button">Sign Out</a>
        <br><br>
      </div>
      <?php require_once('common_footer.html');?>
   </body>

</html>
