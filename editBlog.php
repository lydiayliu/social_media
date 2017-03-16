<!DOCTYPE html>
<?php
$bp_ID = $_GET['blogID'];

include("dbconfig.php");
session_start();

if (isset($_SESSION['login_user'])) {
    $user_email = $_SESSION['login_user'];
} else {
    $user_email = "error";
}

$load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
$user_accountID = mysqli_fetch_assoc(mysqli_query($conn, $load_accountID))['accountID'];

$select_blog_query = "SELECT accountID, title, text, timestamp, blogID FROM Blog WHERE blogID = $bp_ID";

$result = mysqli_query($conn, $select_blog_query)
        or die('Error making saveToDatabase query' . mysql_error());

$BP = mysqli_fetch_array($result);

$content = $BP[2];
$title = $BP[1];

?>     
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Edit Blog Post</title>

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
                            <span class="subheading">Changed Your Mind On Something?</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <form name="editBlogPost" action='blogPost.php?blogID=<?php echo $bp_ID ?>' id="editedBlogPost" method='post'>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Title</label>
                                <input type="text" class="form-control" value="<?php echo htmlentities($title) ?>" name="title" required data-validation-required-message="Title">
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Content</label>
                                <textarea rows="10" class="form-control" name="content" required data-validation-required-message="Content"><?php echo htmlentities($content) ?></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <a class="btn btn-default" href="blogPost.php?blogID=<?php echo $bp_ID ?>">Cancel</a>
                                <button type="submit" class="btn btn-default">Post</button>
                            </div>
                        </div>

                    </form>
                    <form name="editBlogPost" action='blog.php?accountID=<?php echo $user_accountID ?>' id="deleteBlogPost" method='post'>
                        <input type="submit" class = "btn btn-warning" name="delete" value="Delete Post" />
                        <input name="delete" type="hidden" id="delete" value="<?php echo $bp_ID ?>" />
                    </form>
                </div>
            </div>
        </div>

        <hr>

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
