<?php
   include('session_admin.php');
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
      <h1>User Update</h1>
        <?php
          echo '<h2>'.$_GET['user_id'];
        ?>
        <form name="frmRegistration" method="post" action="">
        	<table border="0" width="500" class="demo-table">

            <div><?php if(isset($message)) echo $message; ?></div>

        		<tr><td>Email Address</td>
        		<td><input type="text" class="demoInputBox" name="email_address" value="<?php echo $user_profile['email_address']; ?>"></td>
        		</tr>
        		<tr><td>Name</td>
        		<td><input type="text" class="demoInputBox" name="name" value="<?php echo $user_profile['name']; ?>"></td>
        		</tr>
        		<tr><td>Password</td>
        		<td><input type="password" class="demoInputBox" name="password" value=""></td>
        		</tr>
        		<tr><td>Confirm Password</td>
        		<td><input type="password" class="demoInputBox" name="confirm_password" value=""></td>
        		</tr>
            <tr><td>Age</td>
        		<td><input type="number" class="demoInputBox" name="age" value="<?php  echo $user_profile['age']; ?>"></td>
        		</tr>
            <tr><td>Country</td>
            <td><input type="text" class="demoInputBox"  name="country" value="<?php  echo $user_profile['country'];?>"></td>
            </tr>
            <tr><td>City</td>
            <td><input type="text" class="demoInputBox"  name="city" value="<?php  echo $user_profile['city']; ?>"></td>
            </tr>
            <tr><td>Introduction</td>
            <td><textarea name="introduction" rows="5" cols="50" value="<?php  echo $user_profile['self_introduction']; ?>"><?php  echo $user_profile['self_introduction']; ?></textarea></td>
            </tr>
            <tr><td>Privacy Setting</td>
            <td><select name="privacy">
                  <option value="public">Public</option>
                  <option value="friends_only">Friends Only</option>
                  <option value="private">Private</option>
                </select>
        		</td>
            </tr>
        	</table>
      	<div><input type="submit" name="submit" value="Update"></div>
      </form>
      <h2 class = "btn btn-info"><a href = "logout.php">Sign Out</a></h2>
      <?php require_once('common_footer.html');?>
   </body>

</html>
