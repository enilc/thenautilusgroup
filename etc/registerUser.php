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



function sendEml($to, $msgHTML, $msgPlainText){

    //Credit: This code comes, mostly, from the PHPMailer documentation

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 2;
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
        echo "Message sent!";
        //Section 2: IMAP
    }
}


/****************************************************
    End Example Code from PHPMailer
****************************************************/

//hash('sha512', $nonce . $cnonce . $data);... Here's an example of a reasonably secure makeRandomString function...

//Credit: https://stackoverflow.com/a/4145848
function makeRandomString($bits = 256) {
    $bytes = ceil($bits / 8);
    $return = '';
    for ($i = 0; $i < $bytes; $i++) {
        $return .= chr(mt_rand(0, 255));
    }
    return $return;
}

function verifyUserDetails($email, $passwordOne, $passwordTwo, $firstName, $lastName, $token){

    if(strcmp($passwordOne, $passwordTwo) || empty($firstName) || 
            empty($lastName) || empty($email) || empty($token) || 
            strlen($passwordOne) < 8 || strlen($passwordTwo) < 8){
        return false;
    }


    //Test if user is already registered
    $dbConn = connectToDatabase();
    $sql = "SELECT email FROM spotter.User WHERE email = :email";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $email))){
        return false;
    } else if ($stmt -> rowCount() > 0) {
        return false;
    } 

    //TODO: I think this is wrong??? I'm tired and making mistakes.

    //Test if user is already registered
    $dbConn = connectToDatabase();
    $sql = "SELECT email, token FROM spotter.users_awaiting_verification WHERE email = :email";
    $stmt = $dbConn -> prepare($sql);
    if(!$stmt -> execute(array(':email' => $email))){
        return false;
    } else if ($stmt -> rowCount() > 0) {
        return false;
    } 


    return true;

//TODO: Get Tokens to Expire
}


$toAddress = 'caleb.allen@gmail.com';

//sendEml('caleb.allen@gmail.com', '<html><body><h2>hello world</h2></body></html>', 'hello world');

/*************************************
        email:'smerd@mailinator.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
/*************************************/


//print_r($_POST);

if(!isset($_POST['email']) || !isset($_POST['passwordOne']) || !isset($_POST['passwordTwo']) || !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['token'])){
    echo '0';
}

$results = verifyUserDetails($_POST['email'], $_POST['passwordOne'], $_POST['passwordTwo'], $_POST['firstName'], $_POST['lastName'], $_POST['token']);

echo ($results) ? '1' : '0';

//$token = hash('sha256', makeRandomString() . $toAddress);
//echo "<html><body><h2>We have a PHP ECHO!!!!</h2><hr /><h2>$results</h2></body></html>";
?>