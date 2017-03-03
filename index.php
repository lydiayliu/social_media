<?php
   include('session.php');
?>
<html>
  <head>
    <title>homepage</title>
    <?php require_once('head.php');?>
  </head>
  <body>
    <h1>welcome to assignment book!<h1>
    <?php
      $selfIDQuery = mysqli_query($conn, "select accountID, name from account where email_address = '$user_check'");
      $row = mysqli_fetch_array($selfIDQuery);
      if (isset($_SESSION['login_user'])){
        echo '<script type="text/javascript">
           window.location = "welcome.php"
      </script>';
      }
    ?>
    <button type="button" id="sign_up" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
      Sign up!
    </button>
    <button type="button" id="login" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off" action:>
      Login!
    </button>
    <script>
      $('#sign_up').on('click', function () {
        var $btn = $(this).button('loading')
        location.href = "register.php";
        $btn.button('reset')
      })
      $('#login').on('click', function () {
        var $btn = $(this).button('loading')
        location.href = "login.php";
        $btn.button('reset')
      })
    </script>
    <?php require_once('common_footer.html');?>
  </body>
</html>
