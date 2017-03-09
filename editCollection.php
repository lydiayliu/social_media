<!DOCTYPE html>
<?php
include("dbconfig.php");
session_start();

if (isset($_SESSION['login_user'])) {
    $user_email = $_SESSION['login_user'];
} else {
    $user_email = "error";
}

$load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
$user_accountID = mysqli_fetch_assoc(mysqli_query($conn, $load_accountID))['accountID'];

$collectionID = $_GET['collectionID'];

//Edit name or description of collection
if (isset($_POST['title']) && isset($_POST['description'])) {
    $newName = $_POST['title'];
    $newDescription = $_POST['description'];

    $editQuery = "UPDATE Collection
                            SET name='$newName',
                                description='$newDescription'
                                WHERE collectionID='$collectionID'";
    mysqli_query($conn, $editQuery);
}

//Add new photo to collection
if (isset($_POST['upload'])) {
    $imgFile = $_FILES['new_image']['name'];
    $tmp_dir = $_FILES['new_image']['tmp_name'];
    $imgSize = $_FILES['new_image']['size'];

    if ($imgFile) {
        $upload_dir = 'images/'; // upload directory	
        $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
        // rename uploading image
        $userpic = rand(1000, 1000000) . $imgFile;

        // allow valid image file formats
        if (in_array($imgExt, $valid_extensions)) {
            // Check file size '5MB'
            if ($imgSize < 5000000) {
                //unlink($upload_dir . $edit_row['userPic']);
                move_uploaded_file($tmp_dir, $upload_dir . $userpic);
            } else {
                $errMSG = "Sorry, your file is too large.";
            }
        } else {
            $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $errMSG = "Sorry, no file was selected.";
    }
    // if no error occured, continue ....
    if (!isset($errMSG)) {
        // insert photo into database
        $uploadQuery = "INSERT INTO BlogPhoto (accountID, isPhoto, image, text, collectionID) " .
                "VALUES ( '$user_accountID', '1', '$userpic', '$imgFile', '$collectionID')";
        $result = mysqli_query($conn, $uploadQuery)
                or die('Error making saveToDatabase query' . mysql_error());
    }
}

//Remove photo from collection
if (isset($_REQUEST["delete"])) {
    $delete = $_REQUEST["delete"];

    // First need information to remove from folder
    $load_query = "SELECT accountID, image, text, timestamp, bpID FROM BlogPhoto WHERE bpID = $delete";
    $load_result = mysqli_query($conn, $load_query)
            or die('Error making deletion query' . mysql_error());
    $to_delete = mysqli_fetch_array($load_result);

    // Remove from database
    $delete_query = "DELETE FROM BlogPhoto WHERE bpID = $delete";

    $delete_result = mysqli_query($conn, $delete_query)
            or die('Error making deletion query' . mysql_error());
    // Then remove from folder
    unlink("images/$to_delete[1]");
}

//Add or remove circle permission to collection
if (isset($_POST['grantcircle'])) {
    $grantCircleID = $_POST['grantcircle'];
    //if access right exist, remove it
    $searchQuery = "SELECT * FROM CircleAccessRight WHERE collectionID = $collectionID AND circleID = $grantCircleID";
    $result = mysqli_query($conn, $searchQuery)
            or die('Error making insert comments query' . mysql_error());
    $existingAccess = mysqli_fetch_array($result);
    if ($existingAccess) {
        echo 'had access';
        $removeQuery = "DELETE FROM CircleAccessRight WHERE collectionID = $collectionID AND circleID = $grantCircleID";
        $result = mysqli_query($conn, $removeQuery)
                or die('Error making delete access right query' . mysql_error());
    } else {
        $insertQuery = "INSERT INTO CircleAccessRight VALUES ( '$collectionID', '$grantCircleID')";
        $result = mysqli_query($conn, $insertQuery)
                or die('Error making insert annotation query' . mysql_error());
    }
}

// load current access rights for circles
$rightCircles = array();
$rightCircleQuery = mysqli_query($conn, "select FriendCircle.circleID, nameOfCircle from CircleAccessRight INNER JOIN FriendCircle ON CircleAccessRight.circleID = FriendCircle.circleID WHERE collectionID = ('$collectionID')");
$k = 0;
while ($row = mysqli_fetch_array($rightCircleQuery)) {
    $rightCircles[$k] = $row;
    $k = $k + 1;
}

// load all circles
$circles = array();
$circleQuery = mysqli_query($conn, "select FriendCircle.circleID, nameOfCircle from CircleMembership INNER JOIN FriendCircle ON CircleMembership.circleID = FriendCircle.circleID WHERE CircleMembership.accountID = ('$user_accountID')");
$k = 0;
while ($row = mysqli_fetch_array($circleQuery)) {
    $circles[$k] = $row;
    $k = $k + 1;
}

// remove individual access right
if (isset($_POST['removeInd'])) {
    $removeIndID = $_POST['removeInd'];
    echo $removeIndID;
    $removeQuery = "DELETE FROM FriendAccessRight WHERE collectionID = $collectionID AND accountID = $removeIndID";
    $result = mysqli_query($conn, $removeQuery)
            or die('Error making delete access right query' . mysql_error());
}

// add individual access right
if (isset($_POST['grantInd'])) {
    $grantIndID = $_POST['grantInd'];
    echo $grantIndID;
    $insertQuery = "INSERT INTO FriendAccessRight VALUES ( '$collectionID', '$grantIndID')";
    $result = mysqli_query($conn, $insertQuery)
            or die('Error making insert access right query' . mysql_error());
}

// function to get all friends of an individual
function getFriends($accountID, $conn) {
    $friends = array();
    $friendsQuery = "SELECT Account.accountID, name FROM Account WHERE "
            . "(Account.accountID in (SELECT Friendship.friend1ID FROM Friendship WHERE Friendship.friend2ID = '$accountID')) OR "
            . "(Account.accountID in (SELECT Friendship.friend2ID FROM Friendship WHERE Friendship.friend1ID = '$accountID'))";
    $friendsQueryResult = mysqli_query($conn, $friendsQuery);
    $k = 0;
    while ($row = mysqli_fetch_array($friendsQueryResult)) {
        $friends[$k] = $row;
        $k = $k + 1;
    }
    return $friends;
}

// get user's friends
$friends = getFriends($user_accountID, $conn);

// load current access rights for individuals
$rightPeople = array();
$rightIDs = array();
$rightPeopleQuery = mysqli_query($conn, "select Account.accountID, name from FriendAccessRight INNER JOIN Account ON FriendAccessRight.accountID = Account.accountID WHERE collectionID = ('$collectionID')");
$k = 0;
while ($row = mysqli_fetch_array($rightPeopleQuery)) {
    $rightPeople[$k] = $row;
    $rightIDs[$k] = $row[0];
    $k = $k + 1;
}
echo count($rightPeople);

// Show friends and their friends in drop down menu
function displayFriendsAndFriends($friends, $conn, $user_accountID, $rightIDs) {
    for ($x = 0; $x < count($friends); $x++) {
        $Friend = $friends[$x];
        if (!in_array($Friend[0], $rightIDs)) {
            echo "<option value = \"$Friend[0]\" >$Friend[1]</option>";
        } else {
            echo "<optgroup label = \"$Friend[1]\">";
        }
        $friendsOfFriend = getFriends($Friend[0], $conn);
        for ($y = 0; $y < count($friendsOfFriend); $y++) {
            $FFriend = $friendsOfFriend[$y];
            // only display if not already with permission
            if ($FFriend[0] != $user_accountID && !in_array($FFriend[0], $rightIDs)) {
                echo "<option value = \"$FFriend[0]\" >&nbsp;&nbsp;&nbsp;$FFriend[1]</option>";
            }
        }
        if (in_array($Friend[0], $rightIDs)) {
            echo "</optgroup>";
        }
    }
}

// Load photos
$query = "SELECT * FROM Collection WHERE collectionID = $collectionID";
$result = mysqli_query($conn, $query)
        or die('Error making select collection query' . mysql_error());
$Collection = mysqli_fetch_array($result);

$photos = array();
$query = "SELECT accountID, image, text, timestamp, bpID FROM BlogPhoto WHERE collectionID = $collectionID ORDER BY timestamp DESC";

$result = mysqli_query($conn, $query)
        or die('Error making select photos query' . mysql_error());

$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $photos[$k] = $row;
    $k = $k + 1;
}

function displayRights($rights) {
    for ($x = 0; $x < count($rights); $x++) {
        $Right = $rights[$x];
        echo "<li>" . $Right[1] . "</li>";
    }
}

function displayPhotos($photos, $collectionID) {

    echo "<div class=\"row text-center\">";

    for ($x = 0; $x < count($photos); $x++) {
        $Photo = $photos[$x];

        echo "
            <div class=\"col-md-3 col-sm-6 hero-feature\">
                <div class=\"thumbnail\">
                    <img src=\"images/$Photo[1]\">
                    <div class=\"caption\">
                        <h3>$Photo[2]</h3>
                        <p>$Photo[3]</p>
                            <form name=\"deletePhoto\" action=\"editCollection.php?collectionID=$collectionID\" id=\"deletePhoto\" method=\"post\">
                                <input type=\"submit\" class = \"btn btn-warning\" name=\"delete\" value=\"Delete Photo\" />
                                <input name=\"delete\" type=\"hidden\" id=\"delete\" value=\"$Photo[4]\" />
                            </form>
                    </div>
                </div>
            </div>         ";
    }
    echo "
        </div>
        ";
}

function displayToBeGranted($togrants) {
    for ($x = 0; $x < count($togrants); $x++) {
        $ToGrant = $togrants[$x];
        echo "
        <option value = \"$ToGrant[0]\">$ToGrant[1]</option>";
    }
}
?>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $Collection[2] ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="css/clean-blog.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <!-- Page Header -->
        <!-- Set your background image for this header on the line below. -->
        <header class="intro-header" style="background-image: url('img/post-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="post-heading">
                            <h1>Edit Your Photo Collection</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Edit Collection -->
        <div class="container">
            <div class="row">
                <?php
                if (isset($errMSG)) {
                    ?>
                    <div class="alert alert-danger">
                        <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
                    </div>
                    <?php
                }
                ?>
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <form name="newCollection" action='editCollection.php?collectionID=<?php echo $collectionID ?>' id="editCollection" method='post'>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Title</label>
                                <input type="text" class="form-control" value="<?php echo $Collection[2] ?>" name="title" required data-validation-required-message="Title">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Description</label>
                                <input type="text" class="form-control" value="<?php echo $Collection[3] ?>" name="description" required data-validation-required-message="Description"></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-default">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php displayPhotos($photos, $collectionID) ?>
            </div>
        </div>

        <!-- Post Content -->
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <form method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label class="control-label">Add Photo</label>
                                <input class="input-group" type="file" name="new_image" accept="image/*" />
                            </div>
                            <button type="submit" name="upload" class="btn btn-default">
                                <span class="glyphicon glyphicon-upload"></span> &nbsp; Upload
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </article>

        <hr>

        <!-- /.panel -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                            Circle Access Rights
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            The following Circles have access right to this collection:
                            <?php displayRights($rightCircles) ?>
                            <form name="grantciricle" action='editCollection.php?collectionID=<?php echo $collectionID ?>' id="grantciricle" method='post'>
                                <select name="grantcircle" onchange="this.form.submit()">
                                    <option value="">Grant or Remove Access Right of Circles</option>
                                    <?php displayToBeGranted($circles) ?>
                                </select>
                            </form>
                        </div>
                        <!-- /.panel-footer -->
                    </div>
                </div>
            </div>
        </div>


        <hr>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                            Individual Access Rights
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            The following Individuals have access right to this collection:
                            <?php displayRights($rightPeople) ?>
                            <form name="removeInd" action='editCollection.php?collectionID=<?php echo $collectionID ?>' id="removeInd" method='post'>
                                <select name="removeInd" onchange="this.form.submit()">
                                    <option value="">Remove Access Right of Individuals</option>
                                    <?php displayToBeGranted($rightPeople) ?>
                                </select>
                            </form>
                            <br>
                            Grant New Access Rights:
                            <form name="grantInd" action='editCollection.php?collectionID=<?php echo $collectionID ?>' id="grantInd" method='post'>
                                <select name="grantInd" onchange="this.form.submit()">
                                    <option value="">Grant Access Right to Friends and their Friends</option>
                                    <?php displayFriendsAndFriends($friends, $conn, $user_accountID, $rightIDs) ?>
                                </select>
                            </form>
                        </div>
                        <!-- /.panel-footer -->
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="pager">
                        <li class="next">
                            <a href="allCollections.php?accountID=<?php echo $Collection[1] ?>">Back to All Collections</a>
                        </li>
                    </ul>
                    <ul class="pager">
                        <li class="next">
                            <form name="deleteCollection" action='allCollections.php?accountID=<?php echo $user_accountID ?>' id="deleteCollection" method='post'>
                                <input type="submit" class = "btn btn-warning" name="delete" value="Delete Collection" />
                                <input name="delete" type="hidden" id="delete" value="<?php echo $collectionID ?>" />
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <?php require_once('common_footer.html'); ?>

        <!-- jQuery -->
        <script src="vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Contact Form JavaScript -->
        <script src="js/jqBootstrapValidation.js"></script>
        <script src="js/contact_me.js"></script>

        <!-- Theme JavaScript -->
        <script src="js/clean-blog.min.js"></script>

    </body>

</html>
