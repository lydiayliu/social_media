<?php
  $servername = "eu-cdbr-azure-west-d.cloudapp.net";
  $username = "b3ecadc66c8aa5";
  $password = "fda61879";
  $dbname = "socialmediadb";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

?>
