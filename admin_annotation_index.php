<?php
   include('session_admin.php');
   $selfIDQuery = mysqli_query($conn, "select accountID from account where email_address = '$user_check'");
   $row = mysqli_fetch_array($selfIDQuery);
   if (isset($_SESSION['login_user'])){
     $selfID = $row['accountID'];
   } else {
     $selfID = "error";
   }

   $annotations_query = mysqli_query($conn, "SELECT * FROM annotation LEFT JOIN account ON annotation.accountID = account.accountID");
   $comments_query = mysqli_query($conn, "SELECT * FROM comment LEFT JOIN account ON comment.accountID = account.accountID ");

   if(count($_POST) > 0  ){
     if(isset($_POST['deleteComment']) ) {
        $bp_ID = $_POST['photoID'];
        $user_accountID = $_POST['accountID'];
        $timeOfComment = $_POST['timestamp'];
        $deleteQuery = "DELETE FROM Comment WHERE photoID = $bp_ID AND accountID = $user_accountID AND timestamp = '$timeOfComment'";
        $result = mysqli_query($conn, $deleteQuery)
                or die('Error making delete comments query' . mysql_error());
     }
     if(isset($_POST['deleteAnnotation']) ){
       $bp_ID = $_POST['photoID'];
       $user_accountID = $_POST['accountID'];
       $timeOfComment = $_POST['timestamp'];
       $deleteQuery = "DELETE FROM annotation WHERE photoID = $bp_ID AND accountID = $user_accountID AND timestamp = '$timeOfComment'";
       $result = mysqli_query($conn, $deleteQuery)
          or die('Error making delete comments query' . mysql_error());
     }
     header("Refresh:0");
   }

?>
<html>

   <head>
      <title>User Index </title>
      <?php require_once('head.php');?>
    </head>

   <body>
     <style type="text/css">
        td
        {
          padding:0 15px 0 15px;
        }
     </style>
     <?php require_once('common_navbar.html');?>
     <script>
       $("#profile_header").addClass("active");
     </script>
      <h1>User Index</h1>
      <?php echo '<table>';
      echo  '<tr> <th> Email Address</th>
                  <th> Name </th>
                  <th> Action </th>
                  <th> Content</th>
                  <th> Photo ID </th>
                  <th> Time </th>
                  <th> Delete? </th
            </tr>';
      while($annotation_row = mysqli_fetch_array($annotations_query)){
        echo '<tr>
              <td><a href="admin_user_update.php?user_id=' . $annotation_row['accountID'] . '">'. $annotation_row['email_address'] . '</a></td>
              <td>' .$annotation_row['name'].'</td>
              <td> Annotation </td>
              <td>'. $annotation_row['annotation'].'</td>
              <td>'. $annotation_row['photoID'].'</td>
              <td>'. $annotation_row['timestamp'].'</td>
              <td>
                <form name="deleteAnnotation" action="admin_annotation_index.php" id="delete" method="post">
                    <input name="deleteAnnotation" type="hidden" id="d}elele" value="1"/>
                    <input name="accountID" type="hidden" id="d}elele" value="'.$annotation_row['accountID'].'"/>
                    <input name="photoID" type="hidden" id="delele" value="'.$annotation_row['photoID'].'"/>
                    <input name="timestamp" type="hidden" id="delele" value="'.$annotation_row['timestamp'].'"/>
                    <button type=\"submit\" class=\"btn-default btn-xs\" >
                        <i class=\"fa fa-times\" aria-hidden=\"true\"></i>
                    </button>
                </form>
              </td>';

      }
      while($comment_row = mysqli_fetch_array($comments_query)){
        echo '<tr>
              <td><a href="admin_user_update.php?user_id=' . $comment_row['accountID'] . '">'. $comment_row['email_address'] . '</a></td>
              <td>' .$comment_row['name'].'</td>
              <td> Commented </td>
              <td>' .substr($comment_row['comment'], 0, 10). '</td>
              <td>'. $comment_row['photoID'].'</td>
              <td>'. $comment_row['timestamp'].'</td>
              <td>
                <form name="deleteAnnotation" action="admin_annotation_index.php" id="delete" method="post">
                    <input name="deleteComment" type="hidden" id="d}elele" value="1"/>
                    <input name="accountID" type="hidden" id="delele" value="'.$comment_row['accountID'].'"/>
                    <input name="photoID" type="hidden" id="delele" value="'.$comment_row['photoID'].'"/>
                    <input name="timestamp" type="hidden" id="delele" value="'.$comment_row['timestamp'].'"/>
                    <button type=\"submit\" class=\"btn-default btn-xs\" >
                        <i class=\"fa fa-times\" aria-hidden=\"true\"></i>
                    </button>
                </form>
              </td>';
      }
      echo '</table>'; ?>
      <h2 class = "btn btn-info"><a href = "logout.php">Sign Out</a></h2>
      <?php require_once('common_footer.html');?>
   </body>

</html>
