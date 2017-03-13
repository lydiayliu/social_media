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

$selection_collection_query = "SELECT collectionID, Collection.accountID, Collection.name, description, Account.name FROM Collection INNER JOIN Account ON Collection.accountID = Account.accountID WHERE collectionID = $collectionID";
$result = mysqli_query($conn, $selection_collection_query)
        or die('Error making select collection query' . mysql_error());
$Collection = mysqli_fetch_array($result);
$title = str_replace("''", "'", $Collection[2]);
$description = str_replace("''", "'", $Collection[3]);

$photos = array();
$select_photos_query = "SELECT accountID, image, title, timestamp, photoID FROM Photo WHERE collectionID = $collectionID ORDER BY timestamp DESC";

$result = mysqli_query($conn, $select_photos_query)
        or die('Error making select photos query' . mysql_error());
$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $photos[$k] = $row;
    $k = $k + 1;
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
                        <a class=\"btn btn-default\" href=\"photo.php?photoID=$Photo[4]\">View</a>
                    </div>
                </div>
            </div>         ";
    }
    echo "
        </div>
        ";
}

function displayEditButton($collectionID) {
    echo "
        <ul class=\"pager\">
            <li class=\"next\">
                <a href=\"editCollection.php?collectionID=$collectionID\">Edit</a>
            </li>
        </ul>";
}
?>     
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $title ?></title>

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
                            <h1><?php echo $title ?></h1>
                            <?php echo $description ?>
                            <span class="meta">Owned by <a href="#"><?php echo $Collection[4] ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Post Content -->
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <?php displayPhotos($photos, $collectionID) ?>
                    </div>
                </div>
            </div>
        </article>

        <hr>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="pager">
                        <li class="next">
                            <a href="allCollections.php?accountID=<?php echo $Collection[1]?>">Back to All Collections</a>
                        </li>
                    </ul>
                    <?php
                    if ($user_accountID == $Collection[1]) {
                        displayEditButton($Collection[0]);
                    }
                    ?>
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
