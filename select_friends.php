<?php
  include('session.php');
  $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  if (isset($_SESSION['login_user'])){
    $selfID = $row['accountID'];
  } else {
    $selfID = "error";
  }

  $selfFriendsQuery = mysqli_query($conn, "select friend2ID from Friendship where friend1ID = ('$selfID') ");//why not = but IN
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $filteredName = mysqli_real_escape_string($conn,$_POST['circleName']);
    if(!empty($_POST['selectedFriends'])) {
      $FriendCircleQuery = mysqli_query($conn,"INSERT INTO FriendCircle (accountID,nameOfCircle) VALUES ($selfID,'$filteredName')");
      $circleID = mysqli_insert_id($conn);
      $insertSelfIntoCircleQuery = mysqli_query($conn,"INSERT INTO CircleMembership (circleID,accountID) VALUES ($circleID,$selfID)");
      foreach($_POST['selectedFriends'] as $eachFriend) {
        mysqli_query($conn,"INSERT INTO CircleMembership (circleID,accountID) VALUES ($circleID,'$eachFriend')");
      }
      echo "<div class=\"alert alert-success\" role=\"alert\">Circle created!</div>";
    }
  }
?>
<html>
  <head>
  <?php require_once('head.php');?>
  </head>
  <body>
    <?php require_once('common_navbar.html');?>
    <script>
      $("#selectedFriends_header").addClass("active");
    </script>
    <h2>Form circle of friends</h2>
    <form method = "post">
      <label>Name of the circle: </label><input type="text" name="circleName" class="box"><br/>
      <?php echo "You have ".mysqli_num_rows($selfFriendsQuery)." friends<br/>"; ?>
      <p>Select friends to form a circle of friends<p>
      <?php while($friendRow = mysqli_fetch_array($selfFriendsQuery)){
        $friendID = $friendRow["friend2ID"];
        $friendNameQuery = mysqli_query($conn, "select name from Account where accountID = ('$friendID') ");
        $nameRow = mysqli_fetch_array($friendNameQuery);
        echo "<input type=\"checkbox\" name=\"selectedFriends[]\" value=\"".$friendID."\">".$nameRow['name']."<br/>";
      }?>
      <br/>
      <input type="submit" value="Submit">
      <br/>
    </form>

    <?php require_once('common_footer.html');?>

  </body>
</html>
