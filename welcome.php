<?php
   include('session.php');
   $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
   $row = mysqli_fetch_array($selfIDQuery);
   if (isset($_SESSION['login_user'])){
     $selfID = $row['accountID'];
   } else {
     $selfID = "error";
   }
?>
<html>

   <head>
      <title>Welcome </title>
      <?php require_once('head.php');?>
    </head>

   <body>
     <?php require_once('common_navbar.html');?>
     <script>
       $("#profile_header").addClass("active");
     </script>
      <h1>Welcome <?php echo $login_session; ?></h1>
      <h2><a href = "FriendList.php">Friend list</a></h2>
      <h2><a href="select_friends.php">Create circle of friends</a></h2>
      <h2><a href="chat_room.php">Chat in circle of friends</a></h2>
      <h2 class = "btn btn-info"><a href = "logout.php">Sign Out</a></h2>
      <?php require_once('common_footer.html');?>
   </body>

</html>
