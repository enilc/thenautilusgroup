<?php
include_once("connection.php");
    
$db = new dbObj();
$connString =  $db->getConnstring();

$path = "images/";
$valid_file_formats = array("jpg", "png", "gif", "bmp","jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];
    //print_R($_POST);die;
    if(strlen($name)) {
        //explode splits the name for the file extension and add the "." at the end of the text and  validates that the extension is  avalid extension
        list($txt, $ext) = explode(".", $name);
        if(in_array($ext,$valid_file_formats)) {
            if($size<(1024*1024)) {
                $user_id = 1;
                $image_name = time().'_'.$user_id.".".$ext;
                $tmp = $_FILES['photoimg']['tmp_name'];
            if(move_uploaded_file($tmp, $path.$image_name)){
                
                $sql = "UPDATE users_image SET image='".$image_name."' WHERE id=$user_id";
        
                $result = mysqli_query($connString, $sql) or die("error to update image data");

                echo json_encode(array('error'=>0, 'msg' => "Successfully!  Uploaded image.."));
            }
            else
                echo json_encode(array('error'=>1, 'msg' => "Image Upload failed..!"));
            }
            else
                echo json_encode(array('error'=>1, 'msg' => "Image file size maximum 1 MB..!"));
        }
        else
            echo json_encode(array('error'=>1, 'msg' => "Invalid file format..!"));
    }
    else
        echo json_encode(array('error'=>1, 'msg' => "Please select image..!"));
    exit;
}
?>