<?php
   include('dbconfig.php');
   session_start();

   $user_check = $_SESSION['login_user'];

   $ses_sql = mysqli_query($conn, "select email_address from account where email_address = '$user_check' and isAdmin = 1 ");

   $row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);

   if (mysqli_num_rows($ses_sql)==0){
     $message = "You do not have admin access";
     echo "<script>alert('$message');document.location='welcome.php'</script>";
   }

   $login_session = $row['email_address'];

   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
   }
?>
