<?php
include("dbconfig.php");
include("friending_functions.php");
#require_once('common_navbar.html');
session_start();
if (isset($_SESSION['login_user'])) {
    $user_email = $_SESSION['login_user'];
} else {
    $user_email = "error";
}
$load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
$user_accountID = mysqli_fetch_assoc(mysqli_query($conn, $load_accountID))['accountID'];

$friend_list = load_friend_invitation($user_accountID, $conn);
$sent_friend_invitation = load_sent_friend_invitation($user_accountID, $conn);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Social Media</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            /* Add a gray background color and some padding to the footer */
            footer {
                background-color: #f2f2f2;
                padding: 25px;
            }

            .carousel-inner img {
                width: 100%; /* Set width to 100% */
                min-height: 200px;
            }

            /* Hide the carousel text when the screen is less than 600 pixels wide */
            @media (max-width: 600px) {
                .carousel-caption {
                    display: none; 
                }
            }
        </style>
    </head>
    <body>

        <!--the common nav bar-->
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Logo</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li id="profile_header"><a href="welcome.php">Profile</a></li>
                        <li id="friendList_header"><a href="FriendList.php">Friend list</a></li>
                        <li id="friendInvitation_header"><a href="friend_invitation.php">Friend invitation</a></li>
                        <li id="selectedFriends_header"><a href="select_friends.php">Create friend circle</a></li>
                        <li id="chatRoom_header"><a href="chat_room.php">Chat room</a></li>
                        <li id="chatRoom_header"><a href="blog.php">Blog</a></li>
                        <li id="chatRoom_header"><a href="allCollections.php">Photo Collections</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">    
            <div class="row">
                <div class="col-sm-3">
                    <h3>    Friend invitation</h3>
                    <?php
                    if ($friend_list->num_rows == 0) {
                        echo "No friend invitations yet. <br>Go get some friends!";
                    } else {
                        while ($row = mysqli_fetch_assoc($friend_list)) {
                            $friend_accountID = $row['accountID'];
                            $privacy_setting = check_privacy_status($friend_accountID, $conn);
                            if (!check_inv_status($user_accountID, $friend_accountID, $conn)) {
                                ?>
                                <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
                                <p><?php
                                    if ($privacy_setting == "public") {
                                        echo "<br>Name: " . $row['name'] . "<br>Email address: " . $row['email_address'] . "<br>Age: " . $row['age'] . "<br>Self-introduction: " . $row['self-introduction'] . "<br>City: " . $row['city'] . "<br>Country: " . $row['country'];
                                    } else {
                                        echo "<br>Name: " . $row['name'] . "<br>City: " . $row['city'];
                                    }
                                    ?></p>
                                <form action="">
                                    <input type="submit" class = "btn btn-warning" name="select" value="Receive" />
                                    <input name="a"  type="hidden" id="a" value= "<?php echo $row['accountID']; ?>" />
                                </form>
                                <form action="">
                                    <input type="submit" class = "btn" name="select" value="Reject" />
                                    <input name="b"  type="hidden" id="b" value= "<?php echo $row['accountID']; ?>" />
                                </form>

                                <br>

                                <?php
                                if (isset($_REQUEST["a"])) {
                                    $a = $_REQUEST["a"];
                                    if ($a == $row['accountID']) {
                                        accept_invitation($user_accountID, $friend_accountID, $conn);
                                        echo "<script>location.href='FriendList.php'</script>";
                                    }
                                }
                                if (isset($_REQUEST["b"])) {
                                    $b = $_REQUEST["b"];

                                    if ($b == $row['accountID']) {
                                        reject_invitation($user_accountID, $friend_accountID, $conn);
                                        echo "<script>location.href='friend_invitation.php'</script>";
                                    }
                                }
                            }
                        }
                    }
                    ?>

                </div>


                <div class="col-sm-3">
                    <h3>    Invitations sent</h3>
                    <?php
                    if ($sent_friend_invitation->num_rows == 0) {
                        echo "No sent invitations yet. <br>Go get some friends!";
                    } else {
                        while ($row = mysqli_fetch_assoc($sent_friend_invitation)) {
                            $friend_accountID = $row['accountID'];
                            $privacy_setting = check_privacy_status($friend_accountID, $conn);
                            $status = check_inv_status($friend_accountID, $user_accountID, $conn);
                            ?>
                            <img src="https://placehold.it/150x80?text=IMAGE" class="img-responsive" style="width:100%" alt="Image">
                            <p><?php
                                if ($privacy_setting == "public") {
                                    echo "<br>Name: " . $row['name'] . "<br>Email address: " . $row['email_address'] . "<br>Age: " . $row['age'] . "<br>Self-introduction: " . $row['self-introduction'] . "<br>City: " . $row['city'] . "<br>Country: " . $row['country'];
                                } else {
                                    echo "<br>Name: " . $row['name'] . "<br>City: " . $row['city'];
                                }
                                if (!$status) {
                                    echo "<br>Status: pending<br><br>";
                                } else {
                                    ?></p>
                                <form action="">
                                    <input type="submit" class = "btn btn-warning" name="select" value="Okay" />
                                    <input name="a"  type="hidden" id="a" value= "<?php echo $row['accountID']; ?>" />
                                </form>
                                <br>

                                <?php
                                $a = $_REQUEST["a"];
                                if ($a == $row['accountID']) {
                                    delete_invitation($user_accountID, $friend_accountID, $conn);
                                    echo "<script>location.href='friend_invitation.php'</script>";
                                }
                            }
                        }
                    }
                    ?>

                </div>


                <div class="col-sm-2">

                </div>

                <div class="col-sm-4">
                    <br><br><br><br>
                    <h4>Searching for a friend:</h4>
                    <br>

                    <div class="row">
                        <form action="Searching_for_friends.php" method = "post">
                            <div class="col-sm-4">
                                <p>Name:
                                    <input type="text" name = "name" class="form-control" placeholder="Jacky" aria-describedby="basic-addon1" > </p>
                            </div> 

                            <div class="col-sm-2">
                                <br>
                                <input type="submit" class = "btn btn-info" name="select" value="Go" />
                            </div></form>

                        <form action="Searching_for_friends.php" method = "post">
                            <div class="col-sm-4">
                                <p>age:
                                    <input type="number" name = "age" class="form-control" placeholder="25" aria-describedby="basic-addon1">  </p>
                            </div> 

                            <div class="col-sm-2">
                                <br>
                                <input type="submit" class = "btn btn-info" name="select" value="Go" />
                            </div></form></div>


                    <div class="row">

                        <form action="Searching_for_friends.php" method = "post">
                            <div class="col-sm-4">
                                <p>City:
                                    <input type="text" name = "city" class="form-control" placeholder="london" aria-describedby="basic-addon1" > </p>
                            </div>
                            <div class="col-sm-2">
                                <br>
                                <input type="submit" class = "btn btn-info" name="select" value="Go" />
                            </div></form>

                        <form action="Searching_for_friends.php" method = "post">
                            <div class="col-sm-4">
                                <p>Country:
                                    <input type="text" name = "country" class="form-control" placeholder="U.K." aria-describedby="basic-addon1"> </p>
                            </div>
                            <div class="col-sm-2">
                                <br>
                                <input type="submit" class = "btn btn-info" name="select" value="Go" />
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <form action="Searching_for_friends.php" method = "post">
                            <div class="col-sm-10">
                                <p>Email address:
                                    <input type="text" name = "email" class="form-control" placeholder="xxx@fake.com" aria-describedby="basic-addon1"> </p>
                            </div>


                            <div class="col-sm-2">
                                <br>
                                <input type="submit" class = "btn btn-info" name="select" value="Go" />
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <form action="Searching_for_friends.php" method = "post">
                            <div class="col-sm-10">
                                <p>Friends of a known friend:
                                    <input type="text" name = "friend_of_f" class="form-control" placeholder="Mary" aria-describedby="basic-addon1"> </p>
                            </div>


                            <div class="col-sm-2">
                                <br>
                                <input type="submit" class = "btn btn-info" name="select" value="Go" />
                            </div>
                        </form>

                    </div>

                </div>
                <hr>

            </div></div>


        <footer class="container-fluid text-center">
            <h5>Database Group 10
            </h5>
            <p>  </p>
        </footer>



    </body>

</html>





