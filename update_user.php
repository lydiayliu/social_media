<?php
include("dbconfig.php");

if (isset($_SESSION['login_user'])) {
    $user_email = $_SESSION['login_user'];
    $load_accountID = "SELECT accountID FROM Account WHERE email_address = '$user_email'";
    $user_accountID = mysqli_fetch_assoc(mysqli_query($conn, $load_accountID))['accountID'];
} else {
    $user_accountID = 0;
}

function update_account($dbconn, $account_id, $user_accountID) {
    $userpassword = mysqli_real_escape_string($dbconn, $_POST["password"]);
    $hashedpassword = password_hash($userpassword, PASSWORD_DEFAULT);
    $age = mysqli_real_escape_string($dbconn, $_POST["age"]);
    $name = mysqli_real_escape_string($dbconn, $_POST["name"]);
    $emailaddress = mysqli_real_escape_string($dbconn, $_POST["email_address"]);
    $city = mysqli_real_escape_string($dbconn, $_POST["city"]);
    $country = mysqli_real_escape_string($dbconn, $_POST["country"]);
    $privacy_setting = mysqli_real_escape_string($dbconn, $_POST["privacy"]);
    $introduction = mysqli_escape_string($dbconn, $_POST["introduction"]);

    $old_email_query = mysqli_query($dbconn, "SELECT email_address FROM account WHERE accountID= '$account_id'");
    $query_row = mysqli_fetch_array($old_email_query);
    $old_email = $query_row['email_address'];
    $is_changed = strcmp($emailaddress, $old_email);

    if (empty($userpassword)) {
        $sql = "UPDATE account
              SET age =           '$age',
                  name =          '$name',
                  email_address = '$emailaddress',
                  city =          '$city',
                  country =       '$country',
                  self_introduction = '$introduction',
                  privacy_setting = '$privacy_setting'

              WHERE accountID = '$account_id'";
    } else {
        $sql = "UPDATE account
              SET password =      '$hashedpassword',
                  age =           '$age',
                  name =          '$name',
                  email_address = '$emailaddress',
                  city =          '$city',
                  country =       '$country',
                  self_introduction = '$introduction',
                  privacy_setting = '$privacy_setting'

              WHERE accountID = '$account_id'";
    }
    if ($dbconn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('Successful - Record Updated!');</script>";
        if ($is_changed && $account_id == $user_accountID) {
            header("location:logout.php");
        }
    } else {
        echo "<script type='text/javascript'>alert('Unsuccessful - ERROR!');</script>";
    }
}

/* Form Required Field Validation */
if (count($_POST) > 0) {
    foreach ($_POST as $key => $value) {
        // skip over password key field if  admin does not want to change user's password
        if ($key == "password" || $key == "confirm_password") {
            continue;
        }
        if (empty($_POST[$key])) {
            $message = ucwords($key) . " field is required";
            break;
        }
    }
    /* Password Matching Validation */
    if ($_POST["password"] != $_POST["confirm_password"]) {
        $message = 'Passwords should be same<br>';
    }

    $old_email_query = mysqli_query($conn, "SELECT accountID, email_address FROM account WHERE accountID= '$user_id'");
    $query_row = mysqli_fetch_array($old_email_query);
    $old_email = $query_row['email_address'];

    /* Email Validation */
    if (!isset($message)) {
        if (!filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid UserEmail";
        }
    }
    if (!isset($message)) {
        $is_changed = strcmp($_POST["email_address"], $old_email);
        if ($is_changed) {
            $new_account_email = mysqli_real_escape_string($conn, $_POST["email_address"]);
            $exist_user_query = "SELECT email_address FROM account WHERE email_address = '$new_account_email' AND accountID != $user_id";
            $result = $conn->query($exist_user_query);
            if ($result->num_rows > 0) {
                $message = "<div class=\"alert alert-danger\">Email already exist!</div>";
            } else {
                update_account($conn, $user_id, $user_accountID);

                header("Refresh:0");
            }
        } else {
            update_account($conn, $user_id, $user_accountID);

            header("Refresh:0");
        }
    }
}
?>
