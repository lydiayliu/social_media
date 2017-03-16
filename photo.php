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

$bp_ID = $_GET['photoID'];

$select_photo_query = "SELECT Photo.accountID, image, title, timestamp, photoID, collectionID, name FROM Photo INNER JOIN Account ON Photo.accountID = Account.accountID WHERE photoID = $bp_ID";

$result = mysqli_query($conn, $select_photo_query)
        or die('Error making select photo query' . mysql_error());

$Photo = mysqli_fetch_array($result);

//Add Annotation
if (isset($_POST['annotation'])) {
    $annotation = $_POST['annotation'];
    //if annotation exists, delete it
    $search_annotation_Query = "SELECT * FROM Annotation WHERE photoID = $bp_ID AND accountID = $user_accountID AND annotation LIKE '%$annotation%'";
    $result = mysqli_query($conn, $search_annotation_Query)
            or die('Error making search annotation query' . mysql_error());
    $anno = mysqli_fetch_array($result);

    if ($anno) {
        $delete_annotation_Query = "DELETE FROM Annotation WHERE photoID = $bp_ID AND accountID = $user_accountID AND annotation LIKE '%$annotation%'";
        $result = mysqli_query($conn, $delete_annotation_Query)
                or die('Error making delete annotation query' . mysql_error());
    } else {
        $insert_annotatin_Query = "INSERT INTO Annotation (photoID, accountID, annotation) 
                            VALUES ( '$bp_ID', '$user_accountID','$annotation')";
        $result = mysqli_query($conn, $insert_annotatin_Query)
                or die('Error making insert annotation query' . mysql_error());
    }
}

//Add Comments
if (isset($_POST['comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $insert_comment_Query = "INSERT INTO Comment (photoID, accountID, comment) 
                            VALUES ( '$bp_ID', '$user_accountID','$comment')";
    $result = mysqli_query($conn, $insert_comment_Query)
            or die('Error making insert comments query' . mysql_error());
}

//Delete Comments
if (isset($_POST['delete'])) {
    $timeOfComment = $_POST['delete'];
    
    $delete_comment_Query = "DELETE FROM Comment WHERE photoID = $bp_ID AND accountID = $user_accountID AND timestamp = '$timeOfComment'";
    
    $result = mysqli_query($conn, $delete_comment_Query)
            or die('Error making delete comment query' . mysql_error());
}

// Get all comments on photo
$comments = array();
$select_comments_query = "SELECT photoID, Comment.accountID, timestamp, comment, name FROM Comment INNER JOIN Account ON Comment.accountID = Account.accountID WHERE photoID = $bp_ID ORDER BY timestamp ASC";
$result = mysqli_query($conn, $select_comments_query)
        or die('Error making select comments query' . mysql_error());
$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $comments[$k] = $row;
    $k = $k + 1;
}

// Get all annotations on photo
$annotations = array();
$select_annotations_query = "SELECT name, annotation FROM Annotation INNER JOIN Account ON Annotation.accountID = Account.accountID WHERE photoID = $bp_ID";
$result = mysqli_query($conn, $select_annotations_query)
        or die('Error making select annotations query' . mysql_error());
$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $annotations[$k] = $row;
    $k = $k + 1;
}

function display($annotations, $type) {
    for ($x = 0; $x < count($annotations); $x++) {
        $Anno = $annotations[$x];

        if ($Anno[1] === $type) {
            echo "<li> ${Anno[0]}\n </li>";
        }
    }
}

function displayComments($comments, $user_accountID) {
    for ($x = 0; $x < count($comments); $x++) {
        $Comment = $comments[$x];

        if ($Comment[1] == $user_accountID) {
            echo "
                    <form name=\"delete\" action=\"photo.php?photoID=$Comment[0]\" id=\"delete\" method=\"post\">
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
        <meta http-equiv="X-UA-Compatible" content="IE = edge">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $Photo[2] ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Theme CSS -->
        <link href="css/clean-blog.min.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src = "https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    </head>

    <body>
        <!-- Page Header -->
        <!-- Set your background image for this header on the line below. -->
        <header class="intro-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <h1><?php echo $Photo[2] ?></h1>
                        <span class="meta"><a href="#"><?php echo $Photo[6] ?></a> uploaded at <?php echo $Photo[3] ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Post Content -->
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                        <img class="img-responsive" src="images/<?php echo $Photo[1] ?>">
                    </div>
                </div>
            </div>
        </article>

        <hr>
        <article>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                        <form name="like" action='photo.php?photoID=<?php echo $bp_ID ?>' id="like" method='post'>
                            <input name="annotation" type="hidden" id="annotation" value="like" />
                            <button type="submit" class="btn btn-default btn-xs" ><i class="glyphicon glyphicon-thumbs-up"></i> Like </button>
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <u><b>Like</b></u>
                                    </li>
                                    <li class="divider"></li>
                                    <?php display($annotations, 'like') ?>
                                </ul>
                            </div>
                        </form>

                        <form name="love" action='photo.php?photoID=<?php echo $bp_ID ?>' id="love" method='post'>
                            <button type="submit" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-heart"></i> Love </button>
                            <input name="annotation" type="hidden" id="annotation" value="love" />
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <u><b>Love</b></u>
                                    </li>
                                    <li class="divider"></li>
                                    <?php display($annotations, 'love') ?>

                                </ul>
                            </div>
                        </form>
                        <form name="sad" action='photo.php?photoID=<?php echo $bp_ID ?>' id="sad" method='post'>
                            <button type="submit" class="btn btn-default btn-xs"><i class="em em-disappointed"></i> Sad  </button>
                            <input name="annotation" type="hidden" id="annotation" value="sad" />
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <u><b>Sad</b></u>
                                    </li>
                                    <li class="divider"></li>
                                    <?php display($annotations, 'sad') ?>

                                </ul>
                            </div>
                        </form>
                        <form name="angry" action='photo.php?photoID=<?php echo $bp_ID ?>' id="angry" method='post'>
                            <button type="submit" class="btn btn-default btn-xs"><i class="em em-angry"></i> Angry</button>
                            <input name="annotation" type="hidden" id="annotation" value="angry" />
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <u><b>Angry</b></u>
                                    </li>
                                    <li class="divider"></li>
                                    <?php display($annotations, 'angry') ?>

                                </ul>
                            </div>
                        </form>

                    </div>
                </div>
        </article>

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
                            <form name="comment" action='photo.php?photoID=<?php echo $bp_ID ?>' id="comment" method='post'>
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

        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="pager">
                        <li class="next">
                            <a href="collection.php?collectionID=<?php echo $Photo[5] ?>">Back to Collection</a>
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
