<?php
   include('session_admin.php');
   $user_id = $_GET['user_id'];
   include('update_user.php');
   $user_id_query =  $ses_sql = mysqli_query($conn, "SELECT * FROM account WHERE accountID = '$user_id'");
   $user_profile = mysqli_fetch_array($user_id_query, MYSQLI_ASSOC);

   if (mysqli_num_rows($user_profile)==0) {
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

   $users_query = mysqli_query($conn, "select * from account");
   $users_row = mysqli_fetch_array($users_query);
?>
<html>

   <head>
      <title>User Index </title>
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
        		<td><input type="text" class="demoInputBox" name="email_address" value="<?php if(isset($_POST['email_address'])) echo $_POST['email_address']; ?>"></td>
        		</tr>
        		<tr><td>Name</td>
        		<td><input type="text" class="demoInputBox" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>"></td>
        		</tr>
        		<tr><td>Password</td>
        		<td><input type="password" class="demoInputBox" name="password" value=""></td>
        		</tr>
        		<tr><td>Confirm Password</td>
        		<td><input type="password" class="demoInputBox" name="confirm_password" value=""></td>
        		</tr>
            <tr><td>Age</td>
        		<td><input type="number" class="demoInputBox" name="age" value="<?php if(isset($_POST['age'])) echo $_POST['age']; ?>"></td>
        		</tr>
            <tr><td>Country</td>
            <td><input type="text" class="demoInputBox"  name="country" value="<?php if(isset($_POST['country'])) echo $_POST['country']; ?>"></td>
            </tr>
            <tr><td>City</td>
            <td><input type="text" class="demoInputBox"  name="city" value="<?php if(isset($_POST['city'])) echo $_POST['city']; ?>"></td>
            </tr>
            <tr><td>Introduction</td>
            <td><textarea name="introduction" rows="5" cols="50" value="<?php if(isset($_POST['introduction'])) echo $_POST['introduction']; ?>"></textarea></td>
            </tr>
            <tr><td>Privacy Setting</td>
            <td><select name="privacy">
                  <option value="public">Public</option>
                  <option value="friends_only">Friends Only</option>
                  <option value="private">Private</option>
                </select>
        		</td>
            </tr>
            <tr>
        		<td><input type="checkbox" name="terms"> I accept Terms and Conditions</td>
        		</tr>
        	</table>
      	<div><input type="submit" name="submit" value="Register"></div>
      </form>
      <h2 class = "btn btn-info"><a href = "logout.php">Sign Out</a></h2>
      <?php require_once('common_footer.html');?>
   </body>

</html>
