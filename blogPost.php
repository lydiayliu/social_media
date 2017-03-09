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

$bp_ID = $_GET['bpID'];

//Add Comments
if (isset($_POST['comment'])) {

    echo "got comment";

    $comment = $_POST['comment'];

    $commentQuery = "INSERT INTO Comment (bpID, accountID, comment) 
                            VALUES ( '$bp_ID', '$user_accountID','$comment')";
    $result = mysqli_query($conn, $commentQuery)
            or die('Error making insert comments query' . mysql_error());
}

//Delete Comments
if (isset($_POST['delete'])) {

    echo "got delete";

    $timeOfComment = $_POST['delete'];

    $deleteQuery = "DELETE FROM Comment WHERE bpID = $bp_ID AND accountID = $user_accountID AND timestamp = '$timeOfComment'";

    $result = mysqli_query($conn, $deleteQuery)
            or die('Error making delete comments query' . mysql_error());
}

// Get comments on blog
$comments = array();
$query = "SELECT bpID, Comment.accountID, timestamp, comment, name FROM Comment INNER JOIN Account ON Comment.accountID = Account.accountID WHERE bpID = $bp_ID ORDER BY timestamp ASC";
$result = mysqli_query($conn, $query)
        or die('Error making select comments query' . mysql_error());
$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $comments[$k] = $row;
    $k = $k + 1;
}

// Edit blog post
if (isset($_POST['title']) && isset($_POST['content'])) {

    function getBlogPost() {
        $blogPost = array();
        $blogPost['title'] = $_POST['title'];
        $blogPost['content'] = str_replace("\n", "<br />", $_POST['content']);
        return $blogPost;
    }

    function saveToDatabase($blogPost, $bp_ID, $conn) {
        $query = "UPDATE BlogPhoto
                            SET title='${blogPost['title']}',
                                text='${blogPost['content']}'
                                WHERE bpID='$bp_ID'";
        mysqli_query($conn, $query);
    }

    $editedBlogPost = getBlogPost();
    saveToDatabase($editedBlogPost, $bp_ID, $conn);
}

$query = "SELECT accountID, title, text, timestamp, bpID FROM BlogPhoto WHERE bpID = $bp_ID";

$result = mysqli_query($conn, $query)
        or die('Error making saveToDatabase query' . mysql_error());

$BP = mysqli_fetch_array($result);

function displayEditButton($bp_ID) {
    echo "
        <ul class=\"pager\">
            <li class=\"next\">
                <a href=\"editBlog.php?bpID=$bp_ID\">Edit</a>
            </li>
        </ul>";
}

function displayComments($comments, $user_accountID) {
    for ($x = 0; $x < count($comments); $x++) {
        $Comment = $comments[$x];

        if ($Comment[1] == $user_accountID) {
            echo "
                    <form name=\"delete\" action=\"blogPost.php?bpID=$Comment[0]\" id=\"delete\" method=\"post\">
                        <div class = \"chat-body clearfix\">
                        <div class = \"header\">
                        <strong class = \"primary-font\">${Comment[4]}</strong>
                            <small class = \"pull-right text-muted\">
                                <i class = \"fa fa-clock-o fa-fw\"></i> ${Comment[2]}
                            </small>
                        </div>
                        <p>
                        ${Comment[3]}
                        <input name=\"delete\" type=\"hidden\" id=\"delele\" value=\"$Comment[2]\" />
                        
                        <small class = \"pull-right text-muted\">
                        <button type=\"submit\" class=\"btn-default btn-xs\" >
                            <i class=\"fa fa-times\" aria-hidden=\"true\"></i>
                        </button>
                        </small>
                    </p>
                </div>
                    </form>";
        } else {
            echo "
                <div class = \"chat-body clearfix\">
                    <div class = \"header\">
                        <strong class = \"primary-font\">${Comment[4]}</strong>
                            <small class = \"pull-right text-muted\">
                                <i class = \"fa fa-clock-o fa-fw\"></i> ${Comment[2]}
                            </small>
                        </div>
                        <p>
                        ${Comment[3]}";
            echo "            
                        </p>
                </div>";
        }
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
                            <h1><?php echo $BP[1] ?></h1>
                            <span class="meta">Posted by <a href="#"><?php echo $BP[0] ?></a> on <?php echo $BP[3] ?></span>
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
                        <?php echo $BP[2] ?>
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
                            <i class="fa fa-comments fa-fw"></i> Comments
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat">
                                <?php displayComments($comments, $user_accountID) ?>
                            </ul>
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <form name="comment" action='blogPost.php?bpID=<?php echo $bp_ID ?>' id="comment" method='post'>
                                <div class="input-group">
                                    <input name="comment" id="comment" type="text" class="form-control input-sm" placeholder="Type your comment here..." required data-validation-required-message="Comment" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-warning btn-sm" type="submit">
                                            Comment
                                        </button>
                                    </span>
                                </div>
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
