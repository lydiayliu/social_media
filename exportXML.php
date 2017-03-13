<?php
include("dbconfig.php");
  $tables = array("Account","Annotation","Blog","CircleAccessRight","CircleMembership","Collection","Comment","FriendAccessRight","FriendCircle","Friendship","Photo","Invitation","Message","Recommendation");
  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
  $xml .="<note
    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">";
  $xml .= "<tables>";
  foreach ($tables as $table_name) {
    $table = $table_name."s";
    $xml .= "<$table>";
    $sql = "SELECT * FROM ".$table_name;
    $result = mysqli_query($conn,$sql);
    if (!$result) {
        die('Invalid query');
    }
    if(mysqli_num_rows($result)>0){
     while($result_array = mysqli_fetch_assoc($result)){
        $xml .= "<".$table_name.">";
        foreach($result_array as $key => $value){
          if (!is_null($value) ) {
             $xml .= "<$key>";
             $xml .= "<![CDATA[$value]]>";
             $xml .= "</$key>";
          }else{
             $xml .= "<$key xsi:nil=\"true\" />";
          }
        }
        $xml.="</".$table_name.">";
      }
    }
    $xml .= "</$table>";
  }
  $xml .= "</tables>";
  $xml .="</note>";
  file_put_contents("xml/export.xml", $xml);
  mysqli_close($conn);
?>
<html>
  <head>
    <title>Export XML</title>
    <?php require_once('head.php'); ?>
  </head>
  <body>
    <a href="xml/export.xml" class="btn btn-default" role="button" download>Download the xml</a>
  </body>
</html>
