<!DOCTYPE html>
<?php
$blogPosts = array();
include("dbconfig.php");
session_start();

if (isset($_SESSION['login_user'])) {
    $user_email = $_SESSION['login_user'];
} else {
    $user_email = "error";
}

$load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
$user_accountID = mysqli_fetch_assoc(mysqli_query($conn, $load_accountID))['accountID'];

$accountID = $_GET['accountID'];
$search = $_POST['search'];

$load_name = "SELECT name FROM Account WHERE accountID = '$accountID'";
$name = mysqli_fetch_assoc(mysqli_query($conn, $load_name))['name'];

$search_blogs_query = "SELECT * FROM (SELECT accountID, title, text, timestamp, blogID FROM Blog WHERE accountID = $accountID ORDER BY timestamp DESC) AS sub WHERE title LIKE '%{$search}%' OR text LIKE '%{$search}%'";

$result = mysqli_query($conn, $search_blogs_query)
        or die('Error making search query' . mysql_error());

$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $blogPosts[$k] = $row;
    $k = $k + 1;
}

function displayBP($blogPosts, $name) {

    echo "  <div class=\"container\">
                    <div class=\"row\">
                    <div class=\"col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1\">";

    for ($x = 0; $x < count($blogPosts); $x++) {
        $BP = $blogPosts[$x];

        $title = str_replace("''", "'", $BP[1]);
        $preview = str_replace("<br />", "\n", $BP[2]);
        $preview = str_replace("''", "'", $preview);
        $preview = substr($preview, 0, 50);

        echo "               
                    <div class=\"post-preview\">
                        <a href=\"blogPost.php?blogID=$BP[4]\">
                            <h2 class=\"post-title\">
                                $title
                            </h2>
                            <h3 class=\"post-subtitle\">
                                $preview
                            </h3>
                        </a>
                        <p class=\"post-meta\">Posted by <a href=\"#\">$name</a> on $BP[3]</p>
                    </div>
                    <hr>
                    ";
    }

    echo "          </div>
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
                            <h1>Search <?php echo $name ?>'s Blog</h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <form name="search" action='searchBlog.php?accountID=<?php echo $accountID ?>' id="search" method='post'>
                        <div class="row control-group">
                            <div class="form-group floating-label-form-group controls">
                                <label>Search</label>
                                <input type="text" class="form-control" value=<?php echo $search ?> name="search" required data-validation-required-message="Search">
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <button class="btn btn-default" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 

        <?php
        if (!empty($blogPosts)) {
            displayBP($blogPosts, $name);
        }
        ?>


        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <!-- Pager -->
                    <ul class="pager">
                        <li class="next">
                            <a href="blog.php?accountID=<?php echo $accountID ?>">Back To Blog</a>
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
