<?php
  include('session.php');
  ob_start();
  $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  $selfID = $row['accountID'];
  $circleQuery = mysqli_query($conn, "select circleID from CircleMembership where accountID = ('$selfID')");
  if(isset($_POST['sendMessage'])){
  $filteredMessage = mysqli_real_escape_string($conn,$_POST['detail']);
  mysqli_query($conn,"INSERT INTO Message (circleID,accountID,content) VALUES ('{$_POST['selectedCircle']}','$selfID','$filteredMessage')");
  }
  $selectedCircleID = $_POST['selectedCircle'];
  $selectedMessageQuery = mysqli_query($conn,"select accountID,content from Message where circleID = ('$selectedCircleID') order by timeStamp");

?>
<html>
  <body>
    <?php
      echo "selected<br/>";
      while($messageRow = mysqli_fetch_array($selectedMessageQuery)){
        $friendID=$messageRow['accountID'];
        $friendNameQuery = mysqli_query($conn, "select name from Account where accountID = '$friendID' ");
        $nameRow = mysqli_fetch_array($friendNameQuery);
        echo $nameRow['name'].": ";
        echo $messageRow['content'];
        echo "<br/>";
      }
    ?>
    <a name="bottomOFThePage"></a>
    <script>
      document.body.scrollTop = document.body.scrollHeight;
    </script>
  </body>
</html>
