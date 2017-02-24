<?php
  include('session.php');
  $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  $selfID = $row['accountID'];
  $selfCircleQuery = mysqli_query($conn, "select nameOfCircle,circleID from FriendCircle where accountID = ('$selfID') ");//why not = but IN

?>
<html>
  <body>
    <h2>Select the circle</h2>
    <form method = "post">
      <?php while($circleRow = mysqli_fetch_array($selfCircleQuery)){
        $nameOfCircle = $circleRow["nameOfCircle"];
        $circleID = $circleRow["circleID"];
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $circleID == $_POST['selectedCircle']) {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\" checked>".$nameOfCircle."<br/>";
        }else {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\">".$nameOfCircle."<br/>";
        }
      }?>
      <br/>
      <input type="submit" value="Submit">
      <input type="text" name="detail">
      <input type="submit" value="send" name="sendMessage">
    </form>
    <?php
      if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['sendMessage'])) {
          $filteredMessage = mysqli_real_escape_string($conn,$_POST['detail']);
          mysqli_query($conn,"INSERT INTO Message (circleID,accountID,content) VALUES ('{$_POST['selectedCircle']}','$selfID','$filteredMessage')");
        }
        $selectedCircleID = $_POST['selectedCircle'];
        $selectedMessageQuery = mysqli_query($conn,"select accountID,content from Message where circleID = ('$selectedCircleID') order by timeStamp");
        while($messageRow = mysqli_fetch_array($selectedMessageQuery)){
          $friendID=$messageRow['accountID'];
          $friendNameQuery = mysqli_query($conn, "select name from Account where accountID = '$friendID' ");
          $nameRow = mysqli_fetch_array($friendNameQuery);
          echo $nameRow['name'].": ";
          echo $messageRow['content'];
          echo "<br/>";
        }
      }
    ?>
    <p><a href = "welcome.php">back to welcome page</a></p>
  </body>
</html>
