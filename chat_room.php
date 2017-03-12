<?php
  include('session.php');
  if (!isset($_SESSION['login_user'])){
    echo '<script type="text/javascript">
       window.location = "index.php"
  </script>';
  }
  $selfIDQuery = mysqli_query($conn, "SELECT accountID FROM account WHERE email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  $selfID = $row['accountID'];
  $circleQuery = mysqli_query($conn, "SELECT circleID FROM CircleMembership WHERE accountID = ('$selfID')");
?>
<html>
  <head>
  <?php require_once('head.php');?>
  <title>Chat Room</title>
  </head>
  <body>
    <?php require_once('common_navbar.html');?>
    <div class="container">
    <script>
      $("#chatRoom_header").addClass("active");
    </script>
    <div class="col-md-6">
    <h2>Select the circle</h2>
    <?php
      $numOfCircleQuery = mysqli_query($conn, "SELECT * FROM CircleMembership WHERE accountID = ('$selfID') ");
      if (mysqli_num_rows($numOfCircleQuery)<2) {
        echo "You have ".mysqli_num_rows($numOfCircleQuery)." circle<br/>";
      }else {
        echo "You have ".mysqli_num_rows($numOfCircleQuery)." circles<br/>";
      }
    ?>
    <?php
      if (isset($_POST["leave"])) {
        $leaveID = $_POST["leave"];
        $friendRemainQuery = mysqli_query($conn, "SELECT accountID FROM CircleMembership WHERE circleID = '$leaveID' ");
        $leave_query = "DELETE FROM CircleMembership WHERE accountID = ('$selfID') AND circleID = '$leaveID'";
        $leave_result = mysqli_query($conn, $leave_query);
        echo mysqli_error($conn)."<br/><br/>";
        if (mysqli_num_rows($friendRemainQuery)==1) {
          $lastCircleRow = mysqli_fetch_array($friendRemainQuery);
          $deleteQuery = "DELETE FROM FriendCircle WHERE circleID = '$leaveID'";
          $delete_result = mysqli_query($conn, $deleteQuery);
          echo mysqli_error($conn)."<br/><br/>";
        }
        echo "<div class=\"alert alert-success\" role=\"alert\">you have already left the circle</div>";
      }
    ?>
    <form role="form" name="roomForm" method = "post" target="chatRoom" action="conversation.php">
      <?php while($circleRow = mysqli_fetch_array($circleQuery)){
        $circleID = $circleRow["circleID"];
        $circleNameQuery = mysqli_query($conn,"SELECT nameOfCircle FROM FriendCircle WHERE circleID = $circleID ORDER BY nameOfCircle");
        $circleNameRow = mysqli_fetch_array($circleNameQuery);
        $nameOfCircle = $circleNameRow['nameOfCircle'];
        echo "<br/>";
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['leave']) && $circleID == $_POST['selectedCircle']) {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\" checked> ".$nameOfCircle."<br/>";
        }else {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\"> ".$nameOfCircle."<br/>";
        }
        $circleFriendIDQuery = mysqli_query($conn, "SELECT accountID FROM CircleMembership WHERE circleID = ('$circleID')");
        echo "<p class=\"help-block\">Circle member: ";
        $selfNameQuery = mysqli_query($conn, "SELECT name FROM Account WHERE accountID = ('$selfID') ");
        $selfNameRow = mysqli_fetch_array($selfNameQuery);
        $selfName=$selfNameRow['name'];
        echo $selfName." ";
        while ($circleFriendIDRow = mysqli_fetch_array($circleFriendIDQuery)) {
          if ($circleFriendIDRow['accountID']!=$selfID) {
            $friendID=$circleFriendIDRow['accountID'];
            $friendNameQuery = mysqli_query($conn, "SELECT name FROM Account WHERE accountID = ('$friendID') ");
            $friendNameRow = mysqli_fetch_array($friendNameQuery);
            $friendName=$friendNameRow['name'];
            echo $friendName." ";
          }
        }
        echo "</p>";
        echo "<button name:\"leave\" value\"".$circleID."\" class=\"btn btn-warning\" type=\"button\" onclick=\"myFunction(".$circleID.")\">leave this circle</button>";
        echo "<br/>";
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
        function myFunction(circleID) {
            var hform = document.createElement("form");
            hform.setAttribute("method", "post");
            hform.setAttribute("action", "");
            hform.setAttribute("id","myForm");
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "leave");
            hiddenField.setAttribute("value", circleID);
            hform.appendChild(hiddenField);
            document.body.appendChild(hform);
            document.getElementById("myForm").submit();
        }
    </script>

    <br/>
    </div>
    </div>
    <?php require_once('common_footer.html');?>

  </body>

</html>
