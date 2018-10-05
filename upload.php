<?php
	require 'aws-autoloader.php';
	require 'etc/user_funct.php';
	include 'etc/db_mediator.php';

	$BUCKET_URL = 'https://s3-us-west-1.amazonaws.com/spottrimages/';

	function addImageToDB($path, $loc, $usr, $date_taken = NULL, $date_added = NULL){
		global $BUCKET_URL;
		$dbInt = new Mediator;
		echo $BUCKET_URL . $path;
		$dbInt -> dbInsert('Photo', array('photo_id','path', 'user_email', 'location', 'date_taken', 'date_added'),
			array(NULL,$BUCKET_URL . $path,$usr,$loc,($date_taken == NULL) ? date('Y-m-d H:i:s') : $date_taken, 
				($date_added == NULL) ? date('Y-m-d H:i:s') : $date_added));
	}


	//Shim to use for interaction. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
        $_POST = json_decode(file_get_contents('php://input'), true);

	function makeToken($path, $bits = 256) {
	    $bytes = ceil($bits / 8);
	    $randomString = '';
	    for ($i = 0; $i < $bytes; $i++) {
	        $randomString .= chr(mt_rand(0, 255));
	    }
	    return hash('sha'.$bits, $randomString . $path);
	}

	function getFileExtension($path){ //This gets the file extension, returns '' if not allowed.
		$extension = pathinfo($path,PATHINFO_EXTENSION);
		$allowable = array('jpg','jpeg','png','gif','bmp');

		if(array_search($extension, $allowable) !== False){
			return $extension;
		} else {
			return '';
		}
	}

	function scrambleFileName($path){
		return (getFileExtension($path) !== '') ? makeToken($path) . '.' . getFileExtension($path) : ''; 
	}


	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;
	// AWS Info
	$bucketName = 'spottrimages';
	$IAM_KEY = 'ADD_KEY_HERE';  //remove before uploading to github
	$IAM_SECRET = 'ADD_SECRET_HERE';  //remove before uploading to github
	// Connect to AWS
	try {
		$s3 = S3Client::factory(
			array(
				'credentials' => array(
					'key' => $IAM_KEY,
					'secret' => $IAM_SECRET
				),
				'version' => 'latest',
				'region'  => 'us-west-1'
			)
		);
	} catch (Exception $e) {
		// We use a die, so if this fails. It stops here. Typically this is a REST call so this would
		// return a json object.
		die("Error: " . $e->getMessage());
	}
	
	// Generates a unqiue random string for the key name.
	// $keyName = $_POST['location_id'] . basename($_FILES["file"]['name']);
	//$keyName = 'test_example/' . basename($_FILES["file"]['name']);

	print_r($_POST['location_id']);

	$keyName = $_POST['location_id'] . '/' . scrambleFileName(basename($_FILES["file"]['name']));
	//$keyName = scrambleFileName(basename($_FILES["file"]['name']));
	// Add it to S3
	$response = 0;
	try {
		// Uploaded:
		$file = $_FILES["file"]['tmp_name'];
		$s3->putObject(
			array(
				'Bucket'=>$bucketName,
				'Key' =>  $keyName,
				'SourceFile' => $file
			)
		);

	} catch (S3Exception $e) {
		die('Error:' . $e->getMessage());
	} catch (Exception $e) {
		die('Error:' . $e->getMessage());
	}



	addImageToDB($keyName,$_POST['location_id'],'smerd@mailinator.com');



?>