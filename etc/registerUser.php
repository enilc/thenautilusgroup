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


//Returns true if these passwords strings are valid
function isPasswordValid($pw1, $pw2){
    if(strlen($pw1) >= 8 && strlen($pw2) >= 8 
        && strcmp($pw1, $pw2) == 0) {
        return true;
    } else {
        return false;
    }
}

//This looks in the database Queue returns the entry for a particular email
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


//Used for testing, this function will use the functions here to verify user details.
function verifyUserDetails($email, $passwordOne, $passwordTwo, $firstName, $lastName, $token){

    //Check for password validity, or an empty required field.
    if(!isPasswordValid($passwordOne, $passwordTwo) || empty($firstName) || 
            empty($lastName) || empty($email) || empty($token)){

        return false;
    }

    //Make sure the user is not already registered in the system.
    if(isUserRegistered($email)){
        return false;
    }

    //If email is not already registered, query the database for the queue entry for this email
    $qEntry = getQueueEntry($email);
    
    //Check to see if there was anything found in the queue
    if(sizeof($qEntry) > 0){
        //Check the token provided against the one in the queue.
        if(strcmp($qEntry[0]['token'],$token) != 0) {
            return false;
        }
    }
   
   return true;
}

//Adds a user entry to the Queue table in the spottr database.
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
        //Now that the entry was added, we send a verification email.
        echo '<h2>Sending Verification Email</h2>';
        sendEml($details['email'], prepEmailBody($details['email'],$token), '');
    }
}

//Remove a user from the registration queue. Most commonly done after verification of email address.
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

//Messy funciton to prep a simple verification email body.
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

//Takes a user in the Queue table and moves them into the User table. Done post verification.
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

function main(){

    //Tests all have these variables set. So this is a check for GET requests.
    if(!isset($_POST['email']) || !isset($_POST['passwordOne']) || !isset($_POST['passwordTwo']) || !isset($_POST['firstName']) || !isset($_POST['lastName'])){

        //This will most commonly be triggered by verificatione mails.
        if(isset($_GET['email']) && isset($_GET['token'])){

            //Load the HTML for presentation.
            require('registerUserPresentationPartOne.php');

            //Check the Queue for the requested email.
            $entry = getQueueEntry($_GET['email']);

            //If we've never heard of the email, redirect them.
            if(sizeof($entry) <= 0) {
                echo "<p>We're not sure how you got here. 
                <a href=\"../login.php\">Perhapse you wish to register for an account?</a></p>";
            } else { //Now we check the email and token against the queue.
                if(strcmp($_GET['email'], $entry[0]['email']) == 0 &&
                    strcmp($_GET['token'], $entry[0]['token']) == 0){
                    //Go ahead and attempt activation because everything checks out.
                    if(activateUserInQueue($_GET['email'])) {
                        echo "<h3>Email Activated!</h3>
                        <p>Please go to <a href=\"../login.php\">the login page.</a> To start spotting!";
                    } else { //This should only happen when a GET request tries to falsify a user token.
                        echo "<h3>Oops. Something went wrong.</h3>
                        <p>Please head over to <a href=\"../login.php\">the login page</a> and re-registering!";
                    }
                //Feedback for corrupted links or malicious requests.
                } else {
                    echo "<h3>Error: Email+Token pairs do not match</h3>
                    <p>Please try <a href=\"../login.php\">registering again</a></p>";
                }
            }

            //Load the bottom half of the html page for user presentation.
            require('registerUserPresentationPartTwo.php');
        } else { //This is the catch-all for weird POST requests. The bot and hacker branch.
            echo '0';
        }
    } else if (isset($_POST['test'])){ //If we are running tests.

        //We make sure the test data are set.
        if(isset($_POST['email']) && isset($_POST['passwordOne']) && isset($_POST['passwordTwo']) && 
            isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['token']))
        {
            $results = verifyUserDetails($_POST['email'], $_POST['passwordOne'], $_POST['passwordTwo'], 
                                            $_POST['firstName'], $_POST['lastName'], $_POST['token']);
        } else { //This is invoked when something is not set in a test POST. (usually invoked by mistaken developer calls.)
            $results = false;
        }

        //Echo results.
        if($results == false){
            echo '0';
        } else if ($results == true){
            echo '1';
        } else {
            echo 'ERROR';
        }
    } else { //We have a post with all expected fields. Most likely from our web form
        require('registerUserPresentationPartOne.php');

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
        require('registerUserPresentationPartTwo.php');
    }
    
}

main();
?>