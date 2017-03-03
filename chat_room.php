<?php
  include('session.php');
  $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  $selfID = $row['accountID'];
  $circleQuery = mysqli_query($conn, "select circleID from CircleMembership where accountID = ('$selfID')");
?>
<html>
  <head>
  <?php require_once('head.php');?>
  </head>
  <body>
    <?php require_once('common_navbar.html');?>
    <script>
      $("#chatRoom_header").addClass("active");
    </script>
    <h2>Select the circle</h2>
    <form role="form" name="roomForm" method = "post" target="chatRoom" action="conversation.php">
      <?php while($circleRow = mysqli_fetch_array($circleQuery)){
        $circleID = $circleRow["circleID"];
        $circleNameQuery = mysqli_query($conn,"select nameOfCircle from FriendCircle where circleID = $circleID ORDER BY nameOfCircle");
        $circleNameRow = mysqli_fetch_array($circleNameQuery);
        $nameOfCircle = $circleNameRow['nameOfCircle'];
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $circleID == $_POST['selectedCircle']) {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\" checked> ".$nameOfCircle."<br/>";
        }else {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\"> ".$nameOfCircle."<br/>";
        }
      }
      ?>
      <br/>
      <input class="btn btn-default" type="submit" value="Select">
      <br/><br/>
      <input class="form-control" type="text" name="detail" placeholder="message">
      <br/>
      <input class="btn btn-default" type="submit" value="send" name="sendMessage">
    </form>

    <script type="text/javascript">
        setInterval(() => document.forms['roomForm']. submit(), 5000);
    </script>
    <iframe name="chatRoom" scr="conversation.php"></iframe>

    <?php require_once('common_footer.html');?>

  </body>

</html>
