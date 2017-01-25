<?php
 // DB connection info, please change for your own machine setup
 $host = "localhost";
 $user = "root"; 
 $pwd = "0987";
 $db = "social_media";
 try{
     $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
     $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
     $sql = "CREATE TABLE registration_tbl(
                 id INT NOT NULL AUTO_INCREMENT, 
                 PRIMARY KEY(id),
                 name VARCHAR(30),
                 email VARCHAR(30),
                 date DATE)";
     $conn->query($sql);
 }
 catch(Exception $e){
     die(print_r($e));
 }
 echo "<h3>Social Media Tables Created.</h3>";
 ?>