<?php
   include('session_admin.php');
   $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
   $row = mysqli_fetch_array($selfIDQuery);
   if (isset($_SESSION['login_user'])){
     $selfID = $row['accountID'];
   } else {
     $selfID = "error";
   }

   $users_query = mysqli_query($conn, "select * from account");

?>
<html>

   <head>
      <title>User Index </title>
      <?php require_once('head.php');?>
    </head>

   <body>
     <?php require_once('common_navbar.html');?>
     <script>
       $("#profile_header").addClass("active");
     </script>
      <h1>User Index</h1>
      <?php echo '<table>';
      echo  '<tr> <th> Email Address</th>
                  <th> User ID </th>
                  <th> Name </th>
                  <th> Role  </th>
            </tr>';
      while($user_row = mysqli_fetch_array($users_query)){
        echo '<tr>
              <td><a href="admin_user_update.php?user_id=' . $user_row['accountID'] . '">'. $user_row['email_address'] . '</a></td>
              <td>' .$user_row['accountID'].'</td>
              <td>' .$user_row['name'].'</td>
              <td>';  if($user_row['isAdmin'] == 1)
                        echo 'Admin</td>';
                      else
                        echo 'User</td>';
              echo '</tr>';
      }
      echo '</table>'; ?>
      <h2 class = "btn btn-info"><a href = "logout.php">Sign Out</a></h2>
      <?php require_once('common_footer.html');?>
   </body>

</html>
