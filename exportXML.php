<?php
  $servername = "localhost";
  $username = "assignment";
  $password = "";
  $dbname = "social_media";
  $tables = array("Account","Annotation","BlogPhoto","CircleAccessRight","CircleMembership","Collection","Comment","FriendAccessRight","FriendCircle","Friendship","Invitation","Message","Recommendation");
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }
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
  header ("Content-Type:text/xml");
  file_put_contents("export.xml", $xml);
  echo $xml;
  echo "<!--database has be exported as export.xml-->";
  mysqli_close($conn);
?>
