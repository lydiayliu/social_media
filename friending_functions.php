<?php
	function load_friend_list($user_accountID,$conn){
  		$query_friend_list = "SELECT Account.name, Account.email_address, Account.age FROM Account WHERE (Account.accountID in (SELECT Friendship.friend1ID FROM Friendship WHERE Friendship.friend2ID = '$user_accountID')) OR (Account.accountID in (SELECT Friendship.friend2ID FROM Friendship WHERE Friendship.friend1ID = '$user_accountID'))";
  		$result = mysqli_query($conn,$query_friend_list);
  		return $result;
  	}

  	function search_for_friends($name,$conn){
    	$search_by_name = "SELECT accountID FROM Account WHERE name LIKE '%$name%'";
    	$friend_ID = mysqli_fetch_assoc(mysqli_query($conn,$search_by_name))['accountID'];
    	$result = search_by_ID($friend_ID,$conn);

    	return $result;
    }

    function search_by_ID($friend_ID,$conn){
    	$query_get_name = "SELECT name, email_address, age FROM Account WHERE accountID = '$friend_ID'";
    	$result = mysqli_query($conn,$query_get_name);
    	return $result;
    }
?>