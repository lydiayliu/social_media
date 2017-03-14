<?php
include("dbconfig.php");
$selecteddb = mysqli_select_db($conn, $dbname);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Import XML</title>
        <?php require_once('head.php'); ?>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                            <label class="control-label">Select XML file</label>
                            <input class="input-group" type="file" name="new_XML" accept=".xml" />
                        </div>
                        <button type="submit" name="upload" class="btn btn-default">
                            <span class="glyphicon glyphicon-upload"></span> &nbsp; Upload
                        </button>
                    </form>
                    <br/>
                </div>
            </div>
            <?php
            //upload XML
            if (isset($_POST['upload'])) {
                $XMLFile = $_FILES['new_XML']['name'];
                $tmp_dir = $_FILES['new_XML']['tmp_name'];
                $XMLSize = $_FILES['new_XML']['size'];
                $upload_dir = ''; // upload directory
                $userXML = "export.xml";
                if ($XMLFile) {
                    $XMLExt = strtolower(pathinfo($XMLFile, PATHINFO_EXTENSION)); // get image extension
                    $valid_extensions = array('xml'); // valid extensions
                    // allow valid image file formats
                    if (!in_array($XMLExt, $valid_extensions)) {
                        $errMSG = "Sorry, only XML file is allowed.";
                    } else {
                        move_uploaded_file($tmp_dir, $upload_dir . $userXML);
                    }
                } else {
                    $errMSG = "Sorry, no file was selected.";
                }
                // if no error occured, continue ....
                if (!isset($errMSG)) {
                    $xmlDoc = new DOMDocument();
                    $xmlDoc->load("xml/export.xml");
                    $xmlObject = $xmlDoc->getElementsByTagName('Account');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "accountID: " . $accountID;
                        $password = $xmlObject->item($i)->getElementsByTagName('password')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>password: " . $password;
                        $isAdmin = $xmlObject->item($i)->getElementsByTagName('isAdmin')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>isAdmin: " . $isAdmin;
                        $age = $xmlObject->item($i)->getElementsByTagName('age')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>age: " . $age;
                        $name = $xmlObject->item($i)->getElementsByTagName('name')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>name: " . $name;
                        $email_address = $xmlObject->item($i)->getElementsByTagName('email_address')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>email_address: " . $email_address;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('city')->item(0)->childNodes->item(0))) {
                            $city = $xmlObject->item($i)->getElementsByTagName('city')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $city = NULL;
                        }
                        echo "<br/>city: " . $city;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('country')->item(0)->childNodes->item(0))) {
                            $country = $xmlObject->item($i)->getElementsByTagName('country')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $country = NULL;
                        }
                        echo "<br/>country: " . $country;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('self_introduction')->item(0)->childNodes->item(0))) {
                            $self_introduction = $xmlObject->item($i)->getElementsByTagName('self_introduction')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $self_introduction = NULL;
                        }
                        echo "<br/>self_introduction: " . $self_introduction;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('privacy_setting')->item(0)->childNodes->item(0))) {
                            $privacy_setting = $xmlObject->item($i)->getElementsByTagName('privacy_setting')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $privacy_setting = NULL;
                        }
                        echo "<br/>privacy_setting: " . $privacy_setting;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Account` (`accountID`, `password`, `isAdmin`, `age`, `name`, `email_address`, `city`, `country`, `self_introduction`, `privacy_setting`) VALUES ('$accountID', '$password', '$isAdmin', '$age', '$name', '$email_address', '$city', '$country', '$self_introduction', '$privacy_setting')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $accountID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Account import done!<br/><br/>";


                    $xmlObject = $xmlDoc->getElementsByTagName('Invitation');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "accountID: " . $accountID;
                        $inviteeID = $xmlObject->item($i)->getElementsByTagName('inviteeID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>inviteeID: " . $inviteeID;
                        $isRejected = $xmlObject->item($i)->getElementsByTagName('isRejected')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>isRejected: " . $isRejected;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Invitation` (`accountID`, `inviteeID`, `isRejected`) VALUES ('$accountID', '$inviteeID', '$isRejected')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $accountID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Invitation import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Friendship');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $friend1ID = $xmlObject->item($i)->getElementsByTagName('friend1ID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "friend1ID: " . $friend1ID;
                        $friend2ID = $xmlObject->item($i)->getElementsByTagName('friend2ID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>friend2ID: " . $friend2ID;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Friendship` (`friend1ID`, `friend2ID`) VALUES ('$friend1ID', '$friend2ID')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $friend1ID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Friendship import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('FriendCircle');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $circleID = $xmlObject->item($i)->getElementsByTagName('circleID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "circleID: " . $circleID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        $nameOfCircle = $xmlObject->item($i)->getElementsByTagName('nameOfCircle')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>nameOfCircle: " . $nameOfCircle;
                        echo "<br/>";
                        $sql = "INSERT INTO  `FriendCircle` (`circleID`, `accountID`, `nameOfCircle`) VALUES ('$circleID', '$accountID', '$nameOfCircle')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $circleID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "FriendCircle import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('CircleMembership');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $circleID = $xmlObject->item($i)->getElementsByTagName('circleID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "circleID: " . $circleID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        echo "<br/>";
                        $sql = "INSERT INTO  `CircleMembership` (`circleID`, `accountID`) VALUES ('$circleID', '$accountID')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $circleID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "CircleMembership import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Message');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $messageID = $xmlObject->item($i)->getElementsByTagName('messageID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "messageID: " . $messageID;
                        $circleID = $xmlObject->item($i)->getElementsByTagName('circleID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>circleID: " . $circleID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        $content = $xmlObject->item($i)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>content: " . $content;
                        $timeStamp = $xmlObject->item($i)->getElementsByTagName('timeStamp')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>timeStamp: " . $timeStamp;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Message` (`messageID`, `circleID`, `accountID`, `content`, `timeStamp`) VALUES ('$messageID', '$circleID', '$accountID', '$content', '$timeStamp')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $messageID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Message import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Blog');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $blogID = $xmlObject->item($i)->getElementsByTagName('blogID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "blogID: " . $blogID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('text')->item(0)->childNodes->item(0))) {
                            $text = $xmlObject->item($i)->getElementsByTagName('text')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $text = NULL;
                        }
                        echo "<br/>text: " . $text;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0))) {
                            $title = $xmlObject->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $title = NULL;
                        }
                        echo "<br/>title: " . $title;
                        $timestamp = $xmlObject->item($i)->getElementsByTagName('timestamp')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>timestamp: " . $timestamp;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Blog` (`blogID`, `accountID`, `text`, `title`, `timestamp`) VALUES ('$blogID', '$accountID', '$text', '$title', '$timestamp')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $blogID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Blog import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Collection');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $collectionID = $xmlObject->item($i)->getElementsByTagName('collectionID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "collectionID: " . $collectionID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        $name = $xmlObject->item($i)->getElementsByTagName('name')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>name: " . $name;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0))) {
                            $description = $xmlObject->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $description = NULL;
                        }
                        echo "<br/>description: " . $description;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Collection` (`collectionID`, `accountID`, `name`, `description`) VALUES ('$collectionID', '$accountID', '$name', '$description')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $collectionID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Collection import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Photo');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $photoID = $xmlObject->item($i)->getElementsByTagName('photoID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "photoID: " . $photoID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('image')->item(0)->childNodes->item(0))) {
                            $image = $xmlObject->item($i)->getElementsByTagName('image')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $image = NULL;
                        }
                        echo "<br/>image: " . $image;
                        if (!is_null($xmlObject->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0))) {
                            $title = $xmlObject->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
                        } else {
                            $title = NULL;
                        }
                        echo "<br/>title: " . $title;
                        $timestamp = $xmlObject->item($i)->getElementsByTagName('timestamp')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>timestamp: " . $timestamp;
                        $collectionID = $xmlObject->item($i)->getElementsByTagName('collectionID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>collectionID: " . $collectionID;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Photo` (`photoID`, `accountID`, `image`, `title`, `timestamp`, `collectionID`) VALUES ('$photoID', '$accountID', '$image', '$title', '$timestamp', '$collectionID')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $photoID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Photo import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Annotation');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $photoID = $xmlObject->item($i)->getElementsByTagName('photoID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "photoID: " . $photoID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        $timestamp = $xmlObject->item($i)->getElementsByTagName('timestamp')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>timestamp: " . $timestamp;
                        $annotation = $xmlObject->item($i)->getElementsByTagName('annotation')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>annotation: " . $annotation;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Annotation` (`photoID`, `accountID`, `timestamp`, `annotation`) VALUES ('$photoID', '$accountID', '$timestamp', '$annotation')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $photoID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Annotation import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Comment');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $photoID = $xmlObject->item($i)->getElementsByTagName('photoID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "photoID: " . $photoID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        $timestamp = $xmlObject->item($i)->getElementsByTagName('timestamp')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>timestamp: " . $timestamp;
                        $comment = $xmlObject->item($i)->getElementsByTagName('comment')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>annotation: " . $annotation;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Comment` (`photoID`, `accountID`, `timestamp`, `comment`) VALUES ('$photoID', '$accountID', '$timestamp', '$comment')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $photoID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Comment import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('Recommendation');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "accountID: " . $accountID;
                        $recommendeeID = $xmlObject->item($i)->getElementsByTagName('recommendeeID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>recommendeeID: " . $recommendeeID;
                        $reason = $xmlObject->item($i)->getElementsByTagName('reason')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>reason: " . $reason;
                        echo "<br/>";
                        $sql = "INSERT INTO  `Recommendation` (`accountID`, `recommendeeID`, `reason`) VALUES ('$accountID', '$recommendeeID', '$reason')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $accountID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "Recommendation import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('CircleAccessRight');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $collectionID = $xmlObject->item($i)->getElementsByTagName('collectionID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "collectionID: " . $collectionID;
                        $circleID = $xmlObject->item($i)->getElementsByTagName('circleID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>circleID: " . $circleID;
                        echo "<br/>";
                        $sql = "INSERT INTO  `CircleAccessRight` (`collectionID`, `circleID`) VALUES ('$collectionID', '$circleID')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $collectionID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "CircleAccessRight import done!<br/><br/>";

                    $xmlObject = $xmlDoc->getElementsByTagName('FriendAccessRight');
                    $itemCount = $xmlObject->length;
                    for ($i = 0; $i < $itemCount; $i++) {
                        $collectionID = $xmlObject->item($i)->getElementsByTagName('collectionID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "collectionID: " . $collectionID;
                        $accountID = $xmlObject->item($i)->getElementsByTagName('accountID')->item(0)->childNodes->item(0)->nodeValue;
                        echo "<br/>accountID: " . $accountID;
                        echo "<br/>";
                        $sql = "INSERT INTO  `FriendAccessRight` (`collectionID`, `accountID`) VALUES ('$collectionID', '$accountID')";
                        mysqli_query($conn, $sql);
                        print "Finished Item $collectionID <br/>";
                        echo mysqli_error($conn) . "<br/><br/>";
                    }
                    echo "FriendAccessRight import done!<br/><br/>";

                    echo "Import done!<br/><br/>";
                    echo mysqli_error($conn);
                }
            }
            if (isset($errMSG)) {
                ?>
                <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
                </div>
                <?php
            }
            ?>
        </div>
    </body>
</html>
