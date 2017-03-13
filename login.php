<?php
include("dbconfig.php");
session_start();
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach($_POST as $key=>$value) {
      if(empty($_POST[$key])) {
        $message = "<div class=\"alert alert-danger\">" . ucwords($key) . " field is required!</div>";
        break;
      }
    }
    if (!isset($message)) {
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
        $message = "<div class=\"alert alert-danger\">Your Login Name or Password is invalid!</div>";
      }
    }
}
$conn->close();
?>

<html>
    <head>
        <?php require_once('head.php'); ?>
        <title>Login Page</title>
    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
                <h1>Assignment Book</h1>
                <p>welcome to assignment book!</p>
            </div>
        </div>
        <div class="container">
            <div ><b>Login</b></div>
            <form action = "" method = "post">
                <label>Email :</label><input type="text" name="email" class="box"><br/>
                <label>Password :</label><input type="password" name="password" class="box"><br/>
                <input class="btn btn-primary" type = "submit" value = " Submit "/><br />
            </form>
            <form action="index.php">
                <input class="btn btn-primary" type="submit" value="Home Page" />
            </form>
            <div><?php if (isset($message)) echo $message; ?></div>
        </div>
        <?php require_once('common_footer.html'); ?>
    </body>
</html>
