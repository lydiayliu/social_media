<?php
 // DB connection info, please change for your own machine setup
 //the following connection is for Azure
  $servername = "eu-cdbr-azure-west-d.cloudapp.net";
  $username = "b3ecadc66c8aa5";
  $password = "fda61879";
  $dbname = "socialmediadb";
//$servername = "localhost";
//$username = "root";
//$password = "0987";
//$dbname = "social_media";
//  
 try{
     //$conn = new PDO( "mysql:host=$servername;dbname=$dbname", $username, $password);
     //$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
     $conn = new mysqli($servername, $username, $password, $dbname);
     $account_sql = "CREATE TABLE `Account` ( `accountID` INT AUTO_INCREMENT NOT NULL ,`password` VARCHAR(255) NOT NULL , `isAdmin` BOOLEAN NOT NULL DEFAULT FALSE, `age` INT(2) NOT NULL , `name` VARCHAR(30) NOT NULL , `email_address` VARCHAR(30) NOT NULL , `city` VARCHAR(15) NULL , `country` VARCHAR(15) NULL , `self_introduction` TEXT NULL ,`privacy_setting` SET('friends_only','public','private','') NULL DEFAULT 'friends_only' , PRIMARY KEY (`accountID`), UNIQUE(email_address)) ENGINE=InnoDB";
     $recommendation_sql = "CREATE TABLE `Recommendation` ( `accountID` INT NOT NULL ,`recommendeeID` INT NOT NULL , `reason` INT NOT NULL , PRIMARY KEY(`accountID`, `recommendeeID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`), FOREIGN KEY (`recommendeeID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $invitation_sql = "CREATE TABLE `Invitation` ( `accountID` INT NOT NULL ,`inviteeID` INT NOT NULL , `isRejected` BOOLEAN NOT NULL DEFAULT FALSE, PRIMARY KEY (`accountID`, `inviteeID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`), FOREIGN KEY (`inviteeID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $blog_sql = "CREATE TABLE `Blog` ( `blogID` INT AUTO_INCREMENT NOT NULL , `accountID`INT NOT NULL , `text` TEXT NOT NULL , `title` VARCHAR(50) NOT NULL,  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY(`blogID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $photo_sql = "CREATE TABLE `Photo` ( `photoID` INT AUTO_INCREMENT NOT NULL , `accountID`INT NOT NULL , `image` BLOB NOT NULL , `title` VARCHAR(50) NOT NULL,  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `collectionID` INT NOT NULL , PRIMARY KEY(`photoID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`), FOREIGN KEY (`collectionID`) REFERENCES Collection(`collectionID`) ON DELETE CASCADE, UNIQUE(`image`(255))) ENGINE=InnoDB";
     $annotation_sql = "CREATE TABLE `Annotation` ( `photoID` INT NOT NULL , `accountID`INT NOT NULL , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `annotation` SET('like', 'love', 'sad', 'angry','')  NOT NULL , PRIMARY KEY (`accountID`, `timestamp`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`), FOREIGN KEY (`photoID`) REFERENCES Photo(`photoID`) ON DELETE CASCADE) ENGINE=InnoDB";
     $comment_sql = "CREATE TABLE `Comment` ( `photoID` INT NOT NULL , `accountID` INT NOT NULL , `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,`comment` TEXT NOT NULL , PRIMARY KEY (`timestamp`, `accountID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`), FOREIGN KEY (`photoID`) REFERENCES Photo(`photoID`) ON DELETE CASCADE) ENGINE=InnoDB";
     $collection_sql = "CREATE TABLE `Collection` ( `collectionID` INT AUTO_INCREMENT NOT NULL , `accountID`INT NOT NULL , `name` VARCHAR(30) NOT NULL , `description` TEXT NULL ,PRIMARY KEY (`collectionID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $circle_sql = "CREATE TABLE `FriendCircle` ( `circleID` INT NOT NULL AUTO_INCREMENT, `accountID` INT NOT NULL , `nameOfCircle` VARCHAR(30) NOT NULL ,PRIMARY KEY (`circleID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $circle_as_sql = "CREATE TABLE `CircleAccessRight` ( `collectionID` INT NOT NULL , `circleID` INT NOT NULL , PRIMARY KEY (`collectionID`,`circleID`), FOREIGN KEY (`collectionID`) REFERENCES Collection(`collectionID`) ON DELETE CASCADE, FOREIGN KEY (`circleID`) REFERENCES FriendCircle(`circleID`) ON DELETE CASCADE) ENGINE=InnoDB";
     $friend_as_sql = "CREATE TABLE `FriendAccessRight` ( `collectionID` INT NOT NULL , `accountID` INT NOT NULL , PRIMARY KEY (`collectionID`,`accountID`), FOREIGN KEY (`collectionID`) REFERENCES Collection(`collectionID`) ON DELETE CASCADE, FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $friend_sql = "CREATE TABLE `Friendship` ( `friend1ID` INT NOT NULL , `friend2ID` INT NOT NULL , PRIMARY KEY (`friend1ID`,`friend2ID`), FOREIGN KEY (`friend1ID`) REFERENCES Account(`accountID`), FOREIGN KEY (`friend2ID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $circlemem_sql = "CREATE TABLE `CircleMembership` ( `circleID` INT NOT NULL , `accountID` INT NOT NULL , PRIMARY KEY (`circleID`,`accountID`), FOREIGN KEY (`circleID`) REFERENCES FriendCircle(`circleID`) ON DELETE CASCADE, FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`)) ENGINE=InnoDB";
     $message_sql = "CREATE TABLE `Message` ( `messageID` INT AUTO_INCREMENT NOT NULL, `circleID` INT NOT NULL, `accountID` INT NOT NULL, `content` TEXT NOT NULL, `timeStamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`messageID`), FOREIGN KEY (`accountID`) REFERENCES Account(`accountID`), FOREIGN KEY (`circleID`) REFERENCES FriendCircle(`circleID`) ON DELETE CASCADE) ENGINE=InnoDB";
     $conn->query($account_sql);
     $conn->query($recommendation_sql);
     $conn->query($invitation_sql);
     $conn->query($collection_sql);
     $conn->query($blog_sql);
     $conn->query($photo_sql);
     $conn->query($annotation_sql);
     $conn->query($comment_sql);
     $conn->query($circle_sql);
     $conn->query($circle_as_sql);
     $conn->query($friend_as_sql);
     $conn->query($friend_sql);
     $conn->query($circlemem_sql);
     $conn->query($message_sql);
 }
 catch(Exception $e){
     die(print_r($e));
 }
 echo "<h3>Social Media Tables Created.</h3>";
 ?>
