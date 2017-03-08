<?php
include("dbconfig.php");

function update_account($dbconn, $account_id){

    $userpassword = mysqli_real_escape_string($dbconn, $_POST["password"]);
    $hashedpassword = password_hash($userpassword, PASSWORD_DEFAULT);
    $age = mysqli_real_escape_string($dbconn, $_POST["age"]);
    $name = mysqli_real_escape_string($dbconn, $_POST["name"]);
    $emailaddress = mysqli_real_escape_string($dbconn, $_POST["email_address"]);
    $city = mysqli_real_escape_string($dbconn, $_POST["city"]);
    $country = mysqli_real_escape_string($dbconn, $_POST["country"]);
    $privacy_setting = mysqli_real_escape_string($dbconn, $_POST["privacy"]);
    $introduction = mysql_escape_string($dbconn, $_POST["introduction"]);

    $sql = "UPDATE account
            SET password =      '$hashedpassword',
                age =           '$age',
                name =          '$name',
                email_address = '$emailaddress',
                city =          '$city',
                country =       '$country',
                self_introduction = '$country',
                privacy_setting = '$privacy_setting'

            WHERE accountID = '$account_id'";

    if ($dbconn->query($sql) === TRUE)
      echo "<script type='text/javascript'>alert('Successful - Record Updated!');</script>";
    else
      echo "<script type='text/javascript'>alert('Unsuccessful - ERROR!');</script>";



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

    update_account($conn);
    header("Refresh:0");

  }
}
?>
