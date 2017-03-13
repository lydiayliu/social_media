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

function isDataValid() {
    $errorMessage = null;
    if (!isset($_POST['title']) or trim($_POST['title']) == '')
        $errorMessage = 'Your blog has no title?';
    else if (!isset($_POST['content']) or trim($_POST['content']) == '')
        $errorMessage = 'Your blog has nothing in it?';
    if ($errorMessage !== null) {
        echo ('<p>Error: $errorMessage</p>');
        return False;
    }
    return True;
}

function getBlogPost() {
    $blogPost = array();
    $blogPost['title'] = str_replace("'", "\'\'", $_POST['title']);
    $blogPost['content'] = str_replace("\n", "<br />", $_POST['content']);
    $blogPost['content'] = str_replace("'", "\'\'", $blogPost['content']);
    return $blogPost;
}

function printTitle($blogPost) {
    echo htmlentities($blogPost['title']);
}

function printContent($blogPost) {
    echo htmlentities($blogPost['content']);
}

function saveToDatabase($blogPost, $user_accountID, $conn) {
    $new_blog_query = "INSERT INTO Blog (accountID, title, text) " .
            "VALUES ( '$user_accountID', '${blogPost['title']}', '${blogPost['content']}')";
            echo $new_blog_query;
    $result = mysqli_query($conn, $new_blog_query)
            or die('Error making new blog query' . mysql_error());
}

if (isDataValid()) {
    $newBlogPost = getBlogPost();
    saveToDatabase($newBlogPost, $user_accountID, $conn);
    $blogPost = $newBlogPost;
}
?>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>New Blog Post</title>

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
        <header class="intro-header" style="background-image: url('img/contact-bg.jpg')">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <div class="page-heading">
                            <span class="subheading">Consider it Done</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Post Title -->
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <h2 class="section-heading"><?PHP printTitle($blogPost); ?></h2>
                    </div>
                </div>
            </div>
        </article>  

        <hr>

        <!-- Post Content -->
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <p>
                            <?PHP printContent($blogPost); ?>
                        </p>
                    </div>
                </div>
            </div>
        </article>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="pager">
                        <li class="next">
                            <a href="blog.php?accountID=<?php echo $user_accountID ?>">Back to Blog</a>
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
