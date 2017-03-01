<?php
  include('session.php');
  $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  $selfID = $row['accountID'];
  $circleQuery = mysqli_query($conn, "select circleID from CircleMembership where accountID = ('$selfID')");
?>
<html>
  <?php require_once('header.php');?>
  <body>
    <?php require_once('common_navbar.html');?>
    <script>
      $("#chatRoom_header").addClass("active");
    </script>
    <h2>Select the circle</h2>
    <form method = "post">
      <?php while($circleRow = mysqli_fetch_array($circleQuery)){
        $circleID = $circleRow["circleID"];
        $circleNameQuery = mysqli_query($conn,"select nameOfCircle from FriendCircle where circleID = $circleID");
        $circleNameRow = mysqli_fetch_array($circleNameQuery);
        $nameOfCircle = $circleNameRow['nameOfCircle'];
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $circleID == $_POST['selectedCircle']) {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\" checked>".$nameOfCircle."<br/>";
        }else {
          echo "<input type=\"radio\" name=\"selectedCircle\" value=\"".$circleID."\">".$nameOfCircle."<br/>";
        }
      }?>
      <br/>
      <input type="submit" value="Select">
      <br/>
      <br/>
      <input type="text" name="detail" placeholder="message">
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

    <?php require_once('common_footer.html');?>
    
  </body>
</html>
