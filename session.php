<?php
   include('dbconfig.php');
   session_start();

   $user_check = $_SESSION['login_user'];

   $ses_sql = mysqli_query($conn, "select email_address from account where email_address = '$user_check' ");

   $row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

   $login_session = $row['email_address'];

   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
   }
?>
