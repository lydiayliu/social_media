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

if (!isset($_GET['accountID'])) {
    $accountID = $user_accountID;
} else {
    $accountID = $_GET['accountID'];
}

$load_name = "SELECT name FROM Account WHERE accountID = '$accountID'";
$name = mysqli_fetch_assoc(mysqli_query($conn, $load_name))['name'];

$collections = array();

function saveToDatabase($collection, $user_accountID, $conn) {
    $query = "INSERT INTO Collection (accountID, name, description) " .
            "VALUES ( '$user_accountID', '${collection['name']}', '${collection['description']}')";
    echo $query;
    $result = mysqli_query($conn, $query)
            or die('Error making saveToDatabase query' . mysql_error());
}

//create new collection
if (isset($_POST['title']) && isset($_POST['description'])) {
    $collection = array();
    $collection['name'] = $_POST['title'];
    $collection['description'] = $_POST['description'];
    saveToDatabase($collection, $user_accountID, $conn);
}

if (isset($_REQUEST["delete"])) {
    $delete = $_REQUEST["delete"];

    $delete_query = "DELETE FROM Collection WHERE collectionID = $delete";

    $delete_result = mysqli_query($conn, $delete_query)
            or die('Error making saveToDatabase query' . mysql_error());
}

$query = "SELECT * FROM Collection WHERE accountID = $accountID";

$result = mysqli_query($conn, $query)
        or die('Error making select collections query' . mysql_error());

$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $collections[$k] = $row;
    $k = $k + 1;
}

function displayCollections($collections) {

    echo "  <div class=\"container\">
                    <div class=\"row\">
                    <div class=\"col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1\">";

    for ($x = 0; $x < count($collections); $x++) {
        $Collection = $collections[$x];

        echo "               
                    <div class=\"post-preview\">
                        <a href=\"collection.php?collectionID=$Collection[0]\">
                            <h2 class=\"post-title\">
                                $Collection[2]
                            </h2>
                            <h3 class=\"post-subtitle\">
                                $Collection[3]
                            </h3>
                        </a>
                    </div>
                    <hr>
                    ";
    }
}

function displayNewCollectionButton() {
    echo "
            <div class = \"container\">
            <div class = \"row\">
                <div class = \"col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1\">
                    <ul class = \"pager\">
                        <li class = \"next\">
                            <a href = \"newCollection.php\">New Collection</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>";
}
?>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Photo Collections</title>

        <!-- Bootstrap Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="css/clean-blog.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <?php require_once('head.php'); ?>
    </head>

    <body>
        <?php require_once('common_navbar.html'); ?>
        <!-- Page Header -->
        <!-- Set your background image for this header on the line below. -->
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1><?php echo $name?>'s Photo Collections</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php displayCollections($collections) ?>

        <?php
        if ($accountID == $user_accountID) {
            displayNewCollectionButton();
        }
        ?>

        <?php require_once('common_footer.html'); ?>
        
        <!--jQuery -->
        <script src = "vendor/jquery/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

        <!-- Contact Form JavaScript -->
        <script src="js/jqBootstrapValidation.js"></script>
        <script src="js/contact_me.js"></script>

        <!-- Theme JavaScript -->
        <script src="js/clean-blog.min.js"></script>

    </body>

</html>
