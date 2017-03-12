<?php
include("dbconfig.php");
?>
<html>
  <head>
    <title>Register Page</title>
    <?php require_once('head.php');?>
  </head>
  <body>
    <div class="container">
    <?php
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
            echo "<div class=\"alert alert-success\">Account created successfully</div>";
        } else {
            echo "Error: " . "<br>" . $dbconn->error;
        }
    }
      /* Form Required Field Validation */

    if (count($_POST) > 0) {
      foreach($_POST as $key=>$value) {
        if(empty($_POST[$key])) {
          $message = "<div class=\"alert alert-danger\">". ucwords($key) . " field is required!</div>";
          break;
        }
      }
      /* Password Matching Validation */
      if($_POST["password"] != $_POST["confirm_password"]){
        $message = '<div class=\"alert alert-danger\">Passwords should be the same!</div>';
      }

      /* Email Validation */
      if(!isset($message)) {
        if (!filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
          $message = "<div class=\"alert alert-danger\">Invalid email!</div>";
        }
      }

      /* Validation to check if Terms and Conditions are accepted */
      if(!isset($message)) {
        if(!isset($_POST["terms"])) {
          $message = "<div class=\"alert alert-warning\">Accept Terms and conditions before submit!</div>";
        }
      }
      if (!isset($message)){
        $new_account_email = mysqli_real_escape_string($conn, $_POST["email_address"]);
        $exist_user_query = "SELECT email_address FROM account WHERE email_address = '$new_account_email'";
        $result = $conn->query($exist_user_query);
        if ($result->num_rows > 0) {
            $message = "<div class=\"alert alert-danger\">Email already exist!</div>";
        }
        else {
          new_account($conn);
          header("location: index.php");
          $conn->close();
        }
      }
    }
    ?>
    <div class="col-md-4">
    <form name="frmRegistration" method="post" action="" class = "form-signin" role = "form">
        <h1 class= "form-signin-heading">Register</h1>
    		<label>Email Address</label>
    		<input type="text" class="form-control" name="email_address" value="<?php if(isset($_POST['email_address'])) echo $_POST['email_address']; ?>">
        <label>Name</label>
    		<input type="text" class="form-control" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>">
        <label>Password</label>
    		<input type="password" class="form-control" name="password" value="">
        <label>Confirm Password</label>
    		<input type="password" class="form-control" name="confirm_password" value="">
    		<label>Age</label>
    		<input type="number" class="form-control" name="age" value="<?php if(isset($_POST['age'])) echo $_POST['age']; ?>">
    		<label>Country</label>
        <input type="text" class="form-control"  name="country" value="<?php if(isset($_POST['country'])) echo $_POST['country']; ?>">
        <label>City</label>
        <input type="text" class="form-control"  name="city" value="<?php if(isset($_POST['city'])) echo $_POST['city']; ?>">
        <label>Introduction</label>
        <textarea name="introduction" class="form-control" rows="5" cols="50" value="<?php if(isset($_POST['introduction'])) echo $_POST['introduction']; ?>"><?php if(isset($_POST['introduction'])) echo $_POST['introduction']; ?></textarea>
        <label>Privacy Setting</label>
        <select name="privacy" class="form-control">
          <option value="public">Public</option>
          <option value="friends_only">Friends Only</option>
          <option value="private">Private</option>
        </select>
        <div class="checkbox"><input type="checkbox" name="terms">I accept Terms and Conditions</div>
        <?php if(isset($message)) echo $message; ?>
  	<div><input type="submit" name="submit" value="Register" class="btn btn-primary"></div>
  </form>
  <form action="index.php">
    <input class="btn btn-primary" type="submit" value="Back to homepage" />
  </form>
  </div>
  <div>
  </body>
</html>
