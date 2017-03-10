<?php
  include("dbconfig.php");
  session_start();

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach($_POST as $key=>$value) {
      if(empty($_POST[$key])) {
        $message = ucwords($key) . " field is required";
        break;
      }
    }
    if (!isset($message) ){
      $user_email = mysqli_real_escape_string($conn, $_POST['email']);
      $password = mysqli_real_escape_string($conn, $_POST['password']);

      $sql = "SELECT * FROM account WHERE email_address = '$user_email'";
      $result = mysqli_query($conn, $sql);
      $count = mysqli_num_rows($result);
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $hashed_password = $row['password'];

      // If result matched $myusername and $mypassword, table row must be 1 row

      if( password_verify($password, $hashed_password) && $count == 1) {
        $_SESSION['login_user'] = $user_email;
        if ($row['isAdmin'] == 1)
          header("location:admin_welcome.php");
        else
          header("location: welcome.php");
      } else {
        $message = "Your Login Name or Password is invalid";
      }
    }
  }
  $conn->close();
?>

<html>
  <head>
    <title>Login Page</title>
  </head>
  <div ><b>Login</b></div>
  <form action = "" method = "post">
    <label>Email :</label><input type="text" name="email" class="box"><br/>
    <label>Password :</label><input type="password" name="password" class="box"><br/>
    <input type = "submit" value = " Submit "/><br />
  </form>
  <form action="register.php" target="_blank" action="">
    <button>Register</button>
  </form>
  <div><?php if(isset($message)) echo $message; ?></div>
  </body>
</html>
