<?php
require_once('db_connect.php');
require_once('user_funct.php');

/****************************************************
    Begin Example Code from PHPMailer
****************************************************/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

//Shim to use for interaction with angularJS. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>

        .glyphicon.fast-right-spinner {
            -webkit-animation: glyphicon-spin-r 1s infinite linear;
            animation: glyphicon-spin-r 1s infinite linear;
        }
        @-webkit-keyframes glyphicon-spin-r {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }
        }

        @keyframes glyphicon-spin-r {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(359deg);
                transform: rotate(359deg);
            }
        }
    </style>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Spotter Registration Page</h3>
                    </div>
                    <div class="panel-body">
                        <?php main(); ?>
                        </div>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>




<?php
function sendEml($to, $msgHTML, $msgPlainText, $debug = 0){

    //Credit: This code comes, mostly, from the PHPMailer documentation

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = $debug;
    //Set the hostname of the mail server
    $mail->Host = 'smtp.gmail.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "negativeinfinity@gmail.com";
    //Password to use for SMTP authentication
    $mail->Password = 'Na5Qm8ahV736H$i';
    //Set who the message is to be sent from
    $mail->setFrom('negativeinfinity@gmail.com', 'Spottr');
    //Set an alternative reply-to address
    $mail->addReplyTo('negativeinfinity@gmail.com', 'The Nautilus Group');
    //Set who the message is to be sent to
    $mail->addAddress($to);
    //Set the subject line
    $mail->Subject = 'Confirm your email address for spottr!';
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($msgHTML);
    //Replace the plain text body with one created manually
    $mail->AltBody = $msgPlainText;
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Verification Email Sent!";
    }
}
/****************************************************
    End Example Code from PHPMailer
****************************************************/

//Credit: https://stackoverflow.com/a/4145848
function makeToken($eml, $bits = 256) {
    $bytes = ceil($bits / 8);
    $randomString = '';
    for ($i = 0; $i < $bytes; $i++) {
        $randomString .= chr(mt_rand(0, 255));
    }
    //return $randomString;
    return hash('sha'.$bits, $randomString . $eml);
}

function isUserRegistered($email){
    //Test if user is already registered
    $dbConn = connectToDatabase();
    $sql = "SELECT email FROM spotter.User WHERE email = :email";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $email))){
        echo 'ERROR: Unable to queury User database.';
    } else if ($stmt -> rowCount() > 0) {
        return true;
    } 
}

function isPasswordValid($pw1, $pw2){
    if(strlen($pw1) >= 8 && strlen($pw2) >= 8 
        && strcmp($pw1, $pw2) == 0) {
        return true;
    } else {
        return false;
    }
}

function getQueueEntry($email){
    $dbConn = connectToDatabase();
    $sql = "SELECT * FROM spotter.Queue WHERE email = :email";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $email))){
        return array();
    } else if ($stmt -> rowCount() > 0) {
        return $stmt -> fetchAll();
    } else {
        return array();
    }
}

function verifyUserDetails($email, $passwordOne, $passwordTwo, $firstName, $lastName, $token){

    if(!isPasswordValid($passwordOne, $passwordTwo) || empty($firstName) || 
            empty($lastName) || empty($email) || empty($token)){

        return false;
    }

    if(isUserRegistered($email)){
        return false;
    }

    $qEntry = getQueueEntry($email);
    
    if(sizeof($qEntry) > 0){
        if(strcmp($qEntry[0]['token'],$token) != 0) {
            return false;
        }
    }
   
   return true;
}

function addUserToQueue($details){
    $token = makeToken($details['email']);
    //Create Queue Entry

    $dbConn = connectToDatabase();
    $sql = "INSERT INTO spotter.Queue (email, password, first_name, last_name, token, registration_date)";
    $sql = $sql . " VALUES (:email,:password,:fn,:ln,:token,NOW()) ";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $details['email'], 
                                ':password' => password_hash($details['passwordOne'],PASSWORD_DEFAULT),
                                ':fn' => $details['firstName'],
                                ':ln' => $details['lastName'],
                                ':token' => $token))){
        echo 'Error with creating user';
        exit(0);
    } else {
        //TODO: Send verifcation email
        echo '<h2>Sending Verification Email</h2>';
        sendEml($details['email'], prepEmailBody($details['email'],$token), '');
    }
}

function deleteUserFromQueue($email){

    $dbConn = connectToDatabase();
    $sql = "DELETE FROM spotter.Queue WHERE email = :email";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $email))){
        return false;
    } else {
        return true;
    }
}

function prepEmailBody($eml, $token){
    $path = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $body = "
    <html>
        <body>
            <h2>Thanks for registering for spottr!</h2>
            <p>
                Your registration is nearly complete. <a href =\"" . $path . "?email=" . $eml;

    $body = $body . "&token=" . $token . "\">Please click on this link to
                verify your email address in our system.</a>

            </p>
            <p>Thanks,<br />The spottr team</p>
        </body>
    </html>
    ";

    return $body;
}

function activateUserInQueue($email){
    $dbConn = connectToDatabase();
    $sql =  "INSERT INTO spotter.User (email, password, first_name, last_name, date_joined)
                SELECT email, password, first_name, last_name, NOW() FROM spotter.Queue 
                WHERE email = :email";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $email))){
        return false;
    } else {
        return deleteUserFromQueue($email);
    }
}



/*************************************
        email:'smerd@mailinator.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
/*************************************/
function main(){
    //$_POST['email'] = 'negativeinfinity@gmail.com';   //In Queue
    //$_POST['email'] = 'caleb.allen@gmail.com';        //Registered
    /*$_POST['email'] = 'smerd@mailinator.com';           //Unregistered
    $_POST['passwordOne'] = 'Welcome123';
    $_POST['passwordTwo'] = 'Welcome123';
    $_POST['firstName'] = 'Charlie';
    if(!isset($_GET['email'])){
        $_POST['lastName'] = 'Chapman';
        }*/

    if(!isset($_POST['email']) || !isset($_POST['passwordOne']) || !isset($_POST['passwordTwo']) || !isset($_POST['firstName']) || !isset($_POST['lastName'])){
        if(isset($_GET['email']) && isset($_GET['token'])){

            $entry = getQueueEntry($_GET['email']);

            //If we've never heard of the email, redirect them.
            if(sizeof($entry) <= 0) {
                echo "<p>We're not sure how you got here. 
                <a href=\"../login.php\">Perhapse you wish to register for an account?</a></p>";
            } else {
                if(strcmp($_GET['email'], $entry[0]['email']) == 0 &&
                    strcmp($_GET['token'], $entry[0]['token']) == 0){
                    if(activateUserInQueue($_GET['email'])) {
                        echo "<h3>Email Activated!</h3>
                        <p>Please go to <a href=\"../login.php\">the login page.</a> To start spotting!";
                    } else {
                        echo "<h3>Oops. Something went wrong.</h3>
                        <p>Please head over to <a href=\"../login.php\">the login page</a> and re-registering!";
                    }
                } else {
                    echo "<h3>Error: Email+Token pairs do not match</h3>
                    <p>Please try <a href=\"../login.php\">registering again</a></p>";
                }
            }
        } else {
            echo '0';
        }
    } else if (isset($_POST['test'])){ //If we are running tests.

        $results = verifyUserDetails($_POST['email'], $_POST['passwordOne'], $_POST['passwordTwo'], 
                                        $_POST['firstName'], $_POST['lastName'], $_POST['token']);
        if($results == false){
            echo '0';
        } else if ($results == true){
            echo '1';
        } else {
            echo 'ERROR';
        }
    } else { //We have a post with all expected fields. Most likely from our web form

        //Remove user from queue if it is already there.
        deleteUserFromQueue($_POST['email']);


        switch (true){ //Test criteria.
            case isUserRegistered($_POST['email']):
            case !isPasswordValid($_POST['passwordOne'], $_POST['passwordTwo']):
            case empty($_POST['firstName']):
            case empty($_POST['lastName']):
                echo 'Missing User Account Information';
                    exit(0);
                break; //should be unnecissary
            default: //This happens when everything is kosher
                addUserToQueue($_POST);
                break;
        }
    }
    
}

//$token = hash('sha256', makeRandomString() . $toAddress);
//echo "<html><body><h2>We have a PHP ECHO!!!!</h2><hr /><h2>$results</h2></body></html>";
?>