<?php
  include('session.php');
  if (!isset($_SESSION['login_user'])){
    echo '<script type="text/javascript">
       window.location = "index.php"
  </script>';
  }
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
    <div class="container">
    <script>
      $("#chatRoom_header").addClass("active");
    </script>
    <div class="col-md-6">
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
        $circleFriendIDQuery = mysqli_query($conn, "select accountID from CircleMembership where circleID = ('$circleID')");
        echo "<p class=\"help-block\">Circle member: ";
        while ($circleFriendIDRow = mysqli_fetch_array($circleFriendIDQuery)) {
          if ($circleFriendIDRow['accountID']!=$selfID) {
            $friendID=$circleFriendIDRow['accountID'];
            $friendNameQuery = mysqli_query($conn, "select name from Account where accountID = ('$friendID') ");
            $friendNameRow = mysqli_fetch_array($friendNameQuery);
            $friendName=$friendNameRow['name'];
            echo $friendName." ";
          }
        }
        echo "</p>";
      }
      ?>
      <br/>
      <input class="btn btn-default" type="submit" value="Select">
      </div>
    <div class="col-md-6">
      <h2>Chat room</h2>
      <iframe name="chatRoom" scr="conversation.php"></iframe>
      <br/><br/>
      <input class="form-control" type="text" name="detail" placeholder="message">
      <br/>
      <input class="btn btn-default" type="submit" value="send" name="sendMessage">
      <br/>
    </form>

    <script type="text/javascript">
        setInterval(function auto(){
          if ($("input[name='selectedCircle']:checked").val()){
            document.forms['roomForm'].submit();
          }
        }, 5000);
    </script>
    <br/>
    </div>
    </div>
    <?php require_once('common_footer.html');?>

  </body>

</html>
