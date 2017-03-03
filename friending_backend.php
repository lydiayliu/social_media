<?php
	include("dbconfig.php");
	include("friending_functions.php");
  	session_start();
  	if (isset($_SESSION['login_user'])){
  		$user_email = $_SESSION['login_user'];
  	} else {
  		$user_email = "error";
  	}
  	$load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
  	$user_accountID = mysqli_fetch_assoc(mysqli_query($conn,$load_accountID))['accountID'];

    
    if (isset($_POST["a"])){
      $add_friend_accountID = $_POST["a"];
      send_invitation($user_accountID,$add_friend_accountID,$conn);
      unset($_POST["a"]);
    } else if (isset($_POST["b"])){
      $delete_friend_accountID = $_POST["b"];
      delete_friend($user_accountID,$delete_friend_accountID,$conn);
      unset($_POST["b"]);
    }
    
    echo "<script>location.href='FriendList.php'</script>";
	
	
 		
?>