<?php

include("dbconfig.php");

function new_account($dbconn){

    $userpassword = mysqli_real_escape_string($dbconn, $_POST["password"]);
    $hashedpassword = password_hash($userpassword, PASSWORD_DEFAULT);
    $age = mysqli_real_escape_string($dbconn, $_POST["age"]);
    $name = mysqli_real_escape_string($dbconn, $_POST["name"]);
    $emailaddress = mysqli_real_escape_string($dbconn, $_POST["email_address"]);
    $city = mysqli_real_escape_string($dbconn, $_POST["city"]);
    $country = mysqli_real_escape_string($dbconn, $_POST["country"]);
    $privacy_setting = mysqli_real_escape_string($dbconn, $_POST["privacy"]);
    $introduction = mysqli_escape_string($dbconn, $_POST["introduction"]);

    $sql = "INSERT INTO account (password, age, name, email_address, city, country, self_introduction, privacy_setting)
    VALUES ('$hashedpassword', '$age', '$name', '$emailaddress', '$city', '$country', '$introduction' ,'$privacy_setting') ";

    if ($dbconn->query($sql) === TRUE) {
        echo "Account created successfully";
    } else {
        echo "Error: " . "<br>" . $dbconn->error;
    }


}
  /* Form Required Field Validation */


if (count($_POST) > 0) {
  foreach($_POST as $key=>$value) {
    if(empty($_POST[$key])) {
      $message = ucwords($key) . " field is required";
      break;
    }
  }
  /* Password Matching Validation */
  if($_POST["password"] != $_POST["confirm_password"]){
    $message = 'Passwords should be same<br>';
  }

  /* Email Validation */
  if(!isset($message)) {
    if (!filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
      $message = "Invalid UserEmail";
    }
  }

  /* Validation to check if Terms and Conditions are accepted */
  if(!isset($message)) {
    if(!isset($_POST["terms"])) {
      $message = "Accept Terms and conditions before submit";
    }
  }
  if (!isset($message)){
    $new_account_email = mysqli_real_escape_string($conn, $_POST["email_address"]);
    $exist_user_query = "SELECT email_address FROM account WHERE email_address = '$new_account_email'";
    $result = $conn->query($exist_user_query);
    if ($result->num_rows > 0) {
        $message = "Email already exist";
    }
    else {
      new_account($conn);
      header("location: index.php");
      $conn->close();
    }
  }
}

?>
<html>
  <body>

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
        <td><textarea name="introduction" rows="5" cols="50" value="<?php if(isset($_POST['introduction'])) echo $_POST['introduction']; ?>"><?php if(isset($_POST['introduction'])) echo $_POST['introduction']; ?></textarea></td>
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
  </body>
</html>
