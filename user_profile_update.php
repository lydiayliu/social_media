<?php
   include('session.php');
   $user_id = $_GET['user_id'];
   include('update_user.php');
   $user_id_query =  mysqli_query($conn, "SELECT * FROM account WHERE accountID = '$user_id'");
   $user_profile = mysqli_fetch_array($user_id_query, MYSQLI_ASSOC);

   if (mysqli_num_rows($user_id_query)==0) {
       echo "<script type='text/javascript'>alert('Account does not exist');</script>";
   }
      else {

   }
   $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
   $row = mysqli_fetch_array($selfIDQuery);
   if (isset($_SESSION['login_user'])){
     $selfID = $row['accountID'];
   } else {
     $selfID = "error";
   }


?>
<html>
   <head>
      <title>User Update</title>
      <?php require_once('head.php');?>
    </head>

   <body>
     <?php require_once('common_navbar.html');?>
     <script>
       $("#profile_header").addClass("active");
     </script>
     <div class="container">
      <div class="col-md-4">
      <h3>User Update</h3>
        <?php
          echo '<h4>'.$_GET['user_id'].'</h4>';
        ?>
        <form name="frmRegistration" method="post" action="" role = "form">
            <div><?php if(isset($message)) echo "<div class=\"alert alert-danger\">".$message."</div>"; ?></div>
        		<label>Email Address</label>
        		<input type="text" class="form-control" name="email_address" value="<?php echo $user_profile['email_address']; ?>">
        		<label>Name</label>
        		<input type="text" class="form-control" name="name" value="<?php echo $user_profile['name']; ?>">
        		<label>Password</label>
        		<input type="password" class="form-control" name="password" value="">
        		<label>Confirm Password</label>
        		<input type="password" class="form-control" name="confirm_password" value="">
            <label>Age</label>
        		<input type="number" class="form-control" name="age" value="<?php  echo $user_profile['age']; ?>">
            <label>Country</label>
            <input type="text" class="form-control"  name="country" value="<?php  echo $user_profile['country'];?>">
            <label>City</label>
            <input type="text" class="form-control"  name="city" value="<?php  echo $user_profile['city']; ?>">
            <label>Introduction</label>
            <textarea name="introduction" class="form-control" rows="5" cols="50" value="<?php  echo $user_profile['self_introduction']; ?>"><?php  echo $user_profile['self_introduction']; ?></textarea>
            <label>Privacy Setting</label>
            <select name="privacy" class="form-control">
              <option value="public">Public</option>
              <option value="friends_only">Friends Only</option>
              <option value="private">Private</option>
            </select>
            <br/>
      	<input type="submit" name="submit" value="Update" class="btn btn-primary">
      </form>
      <a href = "logout.php" class = "btn btn-primary" role="button">Sign Out</a>
      <br/>
    </div>
    </div>
      <?php require_once('common_footer.html');?>
   </body>

</html>
