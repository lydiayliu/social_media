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

$bp_ID = $_GET['blogID'];

function getBlogPost() {
    $blogPost = array();
    $blogPost['title'] = str_replace("'", "\'\'", $_POST['title']);
    $blogPost['content'] = str_replace("\n", "<br />", $_POST['content']);
    $blogPost['content'] = str_replace("'", "\'\'", $blogPost['content']);
    return $blogPost;
}

function saveToDatabase($blogPost, $bp_ID, $conn) {
    $update_blog_query = "UPDATE Blog
                            SET title='${blogPost['title']}',
                                text='${blogPost['content']}'
                                WHERE blogID='$bp_ID'";
    mysqli_query($conn, $update_blog_query);
}

// Edit blog post
if (isset($_POST['title']) && isset($_POST['content'])) {
    $editedBlogPost = getBlogPost();
    saveToDatabase($editedBlogPost, $bp_ID, $conn);
}

$select_blog_query = "SELECT Blog.accountID, title, text, timestamp, blogID, name FROM Blog INNER JOIN Account on Blog.accountID = Account.accountID WHERE blogID = $bp_ID";

$result = mysqli_query($conn, $select_blog_query)
        or die('Error making select blog query' . mysql_error());

$BP = mysqli_fetch_array($result);

$title = str_replace("''", "'", $BP[1]);
$content = str_replace("''", "'", $BP[2]);

function displayEditButton($bp_ID) {
    echo "
        <ul class=\"pager\">
            <li class=\"next\">
                <a href=\"editBlog.php?blogID=$bp_ID\">Edit</a>
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

        <title><?php echo $BP[1] ?></title>

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
                            <h1><?php echo htmlentities($title) ?></h1>
                            <span class="meta">Posted by <a href="#"><?php echo $BP[5] ?></a> on <?php echo $BP[3] ?></span>
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
                        <?php echo $content ?>
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
                            <a href="blog.php?accountID=<?php echo $BP[0] ?>">Back to Blog</a>
                        </li>
                    </ul>
                    <?php
                    if ($user_accountID == $BP[0]) {
                        displayEditButton($bp_ID);
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
