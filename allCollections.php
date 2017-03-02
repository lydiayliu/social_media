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

echo($user_accountID);

$collections = array();

$accountID = $_GET['accountID'];

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

        <title>Blog</title>

        <!-- Bootstrap Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="css/clean-blog.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    </head>

    <body>

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-custom navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        Menu <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.html">Start Bootstrap</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a href="about.html">About</a>
                        </li>
                        <li>
                            <a href="post.html">Sample Post</a>
                        </li>
                        <li>
                            <a href="contact.html">Contact</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>

        <!-- Page Header -->
        <!-- Set your background image for this header on the line below. -->
        <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="site-heading">
                            <h1>My Photo Collections</h1>
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

        <!--Footer -->
        <footer>
            <div class = "container">
                <div class = "row">
                    <div class = "col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <ul class = "list-inline text-center">
                            <li>
                                <a href = "#">
                                    <span class = "fa-stack fa-lg">
                                        <i class = "fa fa-circle fa-stack-2x"></i>
                                        <i class = "fa fa-twitter fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href = "#">
                                    <span class = "fa-stack fa-lg">
                                        <i class = "fa fa-circle fa-stack-2x"></i>
                                        <i class = "fa fa-facebook fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href = "#">
                                    <span class = "fa-stack fa-lg">
                                        <i class = "fa fa-circle fa-stack-2x"></i>
                                        <i class = "fa fa-github fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <p class = "copyright text-muted">Copyright &copy;
                            Social Media DB10 2017</p>
                    </div>
                </div>
            </div>
        </footer>

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
