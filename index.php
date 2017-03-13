<?php
   include('session.php');
?>
<html>
  <head>
    <title>homepage</title>
    <?php require_once('head.php');?>
  </head>
  <body>
    <div class="container">
      <div class="jumbotron">
        <h1>Assignment Book</h1>
        <p>welcome to assignment book!</p>
      </div>
    </div>
    <?php
//      if (isset($_SESSION['login_user'])){
//        echo '<script type="text/javascript">
//           window.location = "welcome.php"
//      </script>';
//      }
    ?>
    <div class="container">
      <button type="button" id="sign_up" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
        Sign up!
      </button>
      <button type="button" id="login" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off" action:>
        Login!
      </button>
      <button type="button" id="importXML" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
        Import XML!
      </button>
      <button type="button" id="exportXML" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
        Export XML!
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
        $('#exportXML').on('click', function () {
          var $btn = $(this).button('loading')
          location.href = "exportXML.php";
          $btn.button('reset')
        })
        $('#importXML').on('click', function () {
          var $btn = $(this).button('loading')
          location.href = "importXML.php";
          $btn.button('reset')
        })
      </script>
      <br><br/>
      <?php require_once('common_footer.html');?>
    </div>
  </body>
</html>
