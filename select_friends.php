<?php
  include('session.php');
  if (!isset($_SESSION['login_user'])){
    echo '<script type="text/javascript">
       window.location = "index.php"
  </script>';
  }
  $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
  $row = mysqli_fetch_array($selfIDQuery);
  if (isset($_SESSION['login_user'])){
    $selfID = $row['accountID'];
  } else {
    $selfID = "error";
  }

  $selfFriendsQuery = mysqli_query($conn, "select friend2ID from Friendship where friend1ID = ('$selfID') ");
  $selfFriendsQuery2 = mysqli_query($conn, "select friend1ID from Friendship where friend2ID = ('$selfID') ");
  $XcircleQuery = mysqli_query($conn, "select circleID from CircleMembership where accountID = ('$selfID')");
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
    <script src="js/jqBootstrapValidation.js"></script>
    <script>
      $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
    </script>
    <?php require_once('common_navbar.html');?>
    <div class="container">
    <script>
      $("#selectedFriends_header").addClass("active");
    </script>
    <div class="col-md-6">
      <h2>Form circle of friends</h2>
      <form method = "post" role = "form">
        <div class="form-group">
          <label class="control-label">Name of the circle: </label>
          <input type="text" name="circleName" class="box" placeholder="Circle Name" required/><br/>
        </div>
        <div class="form-group">
          <?php echo "You have ".(mysqli_num_rows($selfFriendsQuery)+mysqli_num_rows($selfFriendsQuery2))." friends<br/>"; ?>
          <p>Select friends to form a circle of friends<p>
          <p class="help-block">select at least one friend</p>
        </div>
        <div class="checkbox">
          <?php
            while($friendRow = mysqli_fetch_array($selfFriendsQuery)){
            $friendID = $friendRow["friend2ID"];
            $friendNameQuery = mysqli_query($conn, "select name from Account where accountID = ('$friendID') ");
            $nameRow = mysqli_fetch_array($friendNameQuery);
            echo "<input type=\"checkbox\" name=\"selectedFriends[]\" value=\"".$friendID."\" data-validation-minchecked-minchecked=\"2\" data-validation-minchecked-message=\"Choose at least one\" >".$nameRow['name']."<br>";
          }
            while($friendRow = mysqli_fetch_array($selfFriendsQuery2)){
            $friendID = $friendRow["friend1ID"];
            $friendNameQuery = mysqli_query($conn, "select name from Account where accountID = ('$friendID') ");
            $nameRow = mysqli_fetch_array($friendNameQuery);
            echo "<input type=\"checkbox\" name=\"selectedFriends[]\" value=\"".$friendID."\" data-validation-minchecked-minchecked=\"2\" data-validation-minchecked-message=\"Choose at least one\" >".$nameRow['name']."<br>";
          }?>
        </div>
        <br/>
        <input type="submit" class="btn btn-default" value="Submit">
        <br/>
      </form>
    </div>
    <div class="col-md-6">
      <h2>Current friend cirle</h2>
      <?php
        while($XcircleRow = mysqli_fetch_array($XcircleQuery)){
          $XcircleID = $XcircleRow["circleID"];
          $XcircleNameQuery = mysqli_query($conn,"select nameOfCircle from FriendCircle where circleID = $XcircleID ORDER BY nameOfCircle");
          $XcircleNameRow = mysqli_fetch_array($XcircleNameQuery);
          $XnameOfCircle = $XcircleNameRow['nameOfCircle'];
          echo "<h5>".$XnameOfCircle."<h5/>";
          $XcircleFriendIDQuery = mysqli_query($conn, "select accountID from CircleMembership where circleID = ('$XcircleID')");
          echo "<p class=\"help-block\">Circle member: ";
          while ($XcircleFriendIDRow = mysqli_fetch_array($XcircleFriendIDQuery)) {
            if ($XcircleFriendIDRow['accountID']!=$selfID) {
              $XfriendID=$XcircleFriendIDRow['accountID'];
              $XfriendNameQuery = mysqli_query($conn, "select name from Account where accountID = ('$XfriendID') ");
              $XfriendNameRow = mysqli_fetch_array($XfriendNameQuery);
              $XfriendName=$XfriendNameRow['name'];
              echo $XfriendName." ";
            }
          }
          echo "</p>";
        }
      ?>

    </div>
    </div>
    <?php require_once('common_footer.html');?>

    <!-- Contact Form JavaScript -->
  </body>
</html>
