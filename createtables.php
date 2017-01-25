<?php
 // DB connection info, please change for your own machine setup
 $host = "localhost";
 $user = "root"; 
 $pwd = "0987";
 $db = "social_media";
 try{
     $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
     $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
     $account_sql = "CREATE TABLE `account` ( `accountID` INT AUTO_INCREMENT NOT NULL ,`password` VARCHAR(8) NOT NULL , `isAdmin` BOOLEAN NOT NULL DEFAULT FALSE, `age` INT(2) NOT NULL , `name` VARCHAR(30) NOT NULL , `email_address` VARCHAR(30) NOT NULL , `city` VARCHAR(15) NULL , `country` VARCHAR(15) NULL , `self-introduction` TEXT NULL ,`privacy_setting` SET('friends_only','public','private','') NULL DEFAULT 'friends_only' , PRIMARY KEY (`accountID`))";
     $recommendation_sql = "CREATE TABLE `Recommendation` ( `accountID` INT AUTO_INCREMENT NOT NULL ,`recommendeeID` INT NOT NULL , `reason` TEXT NULL , PRIMARY KEY(`accountID`, `recommendeeID`))";
     $invitation_sql = "CREATE TABLE `Invitation` ( `accountID` INT NOT NULL ,`inviteeID` INT NOT NULL , `isAccepted` BOOLEAN NOT NULL DEFAULT FALSE, PRIMARY KEY (`accountID`, `inviteeID`))";
     $bp_sql = "CREATE TABLE `BlogPhoto` ( `bpID` INT AUTO_INCREMENT NOT NULL , `accountID`INT NOT NULL , `isPhoto` BOOLEAN NOT NULL DEFAULT FALSE , `content`BLOB NOT NULL , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY(`bpID`))";
     $annotation_sql = "CREATE TABLE `Annotation` ( `bpID` INT NOT NULL , `accountID`INT NOT NULL , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `annotation` SET('like', 'love', 'funny', 'sad', '')  NOT NULL , PRIMARY KEY (`bpID`,`timestamp`, `accountID`))";
     $comment_sql = "CREATE TABLE `Comment` ( `bpID` INT NOT NULL , `accountID` INT NOT NULL , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,`comment` TEXT NOT NULL , PRIMARY KEY (`bpID`, `timestamp`, `accountID`))";
     $collection_sql = "CREATE TABLE `Collection` ( `collectionID` INT AUTO_INCREMENT NOT NULL , `accountID`INT NOT NULL , `name` INT NOT NULL , `description` TEXT NULL ,PRIMARY KEY (`collectionID`))";
     $collectmem_sql = "CREATE TABLE `CollectionMembership` ( `bpID` INT NOT NULL ,`collectionID` INT NOT NULL , PRIMARY KEY (`bpID`, `collectionID`))";
     $circle_sql = "CREATE TABLE `FriendCircle` ( `circleID` INT NOT NULL AUTO_INCREMENT, `accountID` INT NOT NULL , `nameOfCircle` VARCHAR(30) NOT NULL ,PRIMARY KEY (`circleID`)) ";
     $accessright_sql = "CREATE TABLE `AccessRight` ( `collectionID` INT NOT NULL , `circleID` INT NOT NULL , PRIMARY KEY (`collectionID`,`circleID`))";
     $friend_sql = "CREATE TABLE `Friendship` ( `friend1ID` INT NOT NULL , `friend2ID` INT NOT NULL , PRIMARY KEY (`friend1ID`,`friend2ID`))";
     $circlemem_sql = "CREATE TABLE `CircleMembership` ( `circleID` INT NOT NULL , `accountID` INT NOT NULL , PRIMARY KEY (`circleID`,`accountID`))";
     $message_sql = "CREATE TABLE `Message` ( `messageID` INT AUTO_INCREMENT NOT NULL, `circleID` INT NOT NULL, `accountID` INT NOT NULL, `content` TEXT NOT NULL, `timeStamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`messageID`))";
     $conn->query($account_sql);
     $conn->query($recommendation_sql);
     $conn->query($invitation_sql);
     $conn->query($bp_sql);
     $conn->query($annotation_sql);
     $conn->query($collection_sql);
     $conn->query($collectmem_sql);
     $conn->query($circle_sql);
     $conn->query($accessright_sql);
     $conn->query($friend_sql);
     $conn->query($circlemem_sql);
     $conn->query($message_sql);
 }
 catch(Exception $e){
     die(print_r($e));
 }
 echo "<h3>Social Media Tables Created.</h3>";
 ?>