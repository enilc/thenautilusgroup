<?php
/***********************************************************
	This page facilitates upload to Amazon's S3 Bucket
	Storage system.

/***********************************************************/

/**********REQUIRED FILES*******************************/
	require 'aws-autoloader.php';
	require 'etc/user_funct.php';
	include 'etc/db_mediator.php';

	use Aws\S3\S3Client;
	use Aws\S3\Exception\S3Exception;

/*********URL Request Processing****************************/

	//Shim to use for interaction. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
        $_POST = json_decode(file_get_contents('php://input'), true);

/*********Global Variables****************************/

	$GLOBALS['BUCKET_URL'] = 'https://s3-us-west-1.amazonaws.com/spottrimages/';
	// AWS Info
	$GLOBALS['BUCKET_NAME'] = 'spottrimages';
	$GLOBALS['IAM_KEY'] = '';  //remove before uploading to github
	$GLOBALS['IAM_SECRET'] = '';  //remove before uploading to github


/*********Member Functions****************************/



	function addImageToDB($path, $loc, $usr, $date_taken = NULL, $date_added = NULL){
		$dbInt = new Mediator;
		$dbInt -> dbInsert('Photo', array('photo_id','path', 'user_email', 'location', 'date_taken', 'date_added'),
			array(NULL,$GLOBALS['BUCKET_URL'] . $path,$usr,$loc,($date_taken == NULL) ? date('Y-m-d H:i:s') : $date_taken, 
				($date_added == NULL) ? date('Y-m-d H:i:s') : $date_added));
	}




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
		if(array_search(strtolower($extension), $allowable) !== False){
			return strtolower($extension);
		} else {
			return '';
		}
	}

	function scrambleFileName($path){
		echo '\n scramble path: ' . $path;
		echo '\n extension: ' . getFileExtension($path);
		echo '\n example token: ' . makeToken($path);
		echo '\n conditional: ' . !empty(getFileExtension($path)); 
		return (!empty(getFileExtension($path))) ? makeToken($path) . '.' . getFileExtension($path) : ''; 
	}


	function uploadImageToS3()
	{


		// Connect to AWS
		try {
			$s3 = S3Client::factory(
				array(
					'credentials' => array(
						'key' => $GLOBALS['IAM_KEY'],
						'secret' => $GLOBALS['IAM_SECRET']
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

		//This is Present Caleb being a bro to Future Caleb. That guy has enough problems.
		if(empty(getFileExtension($_FILES['file']['name']))){
	        header('HTTP/1.1 500 File Upload Error');
	        header('Content-Type: application/json; charset=UTF-8');
	        die(json_encode(array('message' => 'ERROR: Improper File Extension', 'code' => 999)));
	        exit(0);
		}

		$keyName = $_POST['location_id'] . '/' . scrambleFileName(basename($_FILES["file"]['name']));
		
		// Add it to S3
		$response = 0;
		try {
			// Uploaded:
			$file = $_FILES["file"]['tmp_name'];

			$s3->putObject(
				array(
					'Bucket'=>$GLOBALS['BUCKET_NAME'],
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
	}

	function main(){
		//the main function's primary purpose is to keep ne'er-do-wells from abusing the upload feature 
		
		if($_SERVER['REQUEST_METHOD'] != 'POST'){ 
			//Anyone not posting a webform will either be a bot or a developer performing testing
			echo scrambleFileName('test.jpg');

		} else if(isset($_FILES["file"]['name']) && isset($_POST['location_id'])){ //If the method has the expected variables
			uploadImageToS3();
		}
	}

	main();

?>