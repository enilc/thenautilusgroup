<?php
    require('db_connect.php');

    //Shim to use for interaction. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
        $_POST = json_decode(file_get_contents('php://input'), true);

    //First test is to just see if we can connect to db_interface.php. Tells angular connection worked
    if (isset($_POST['test'])){
        echo $_POST['test'];
    //This test makes sure that the 'key' can be used to access the page. Better than nothing, but not much.
    } else if ($_POST['key'] == 'B52C106C63CB00C850584523FB0EC12'){
        //This tests to see if POSTS are being correctly sent to db_interface.php
        if(isset($_POST['key_test']) && $_POST['key_test'] == 'true'){
            echo 'Key Test Passed';
        //This allows SQL inserts
        } else if($_POST['action'] == 'insert'){
            $dbConn = connectToDatabase();
            //I feel like this needs to be done better. Will noodle with it.
            $sql = 'INSERT INTO spotter.' . $_POST['table'] . ' (';
            $first = true;
            foreach($_POST['columns'] as $col){
                $sql .= (!$first ? ', ': '') . $col;
                $first = false;
            }
            $sql .= ') VALUES (';
            $first = true;
            foreach($_POST['values'] as $val){
                $sql .= (!$first ? '\', \'': '\'') . $val;
                $first = false;
            }
            $sql .= '\')';
            $stmt = $dbConn -> prepare($sql);
            try {
                $status = $stmt->execute();
                if($status){
                    echo "success";
                } else {
                    echo "failure: " . $sql;
                }
            }
            catch (PDOException $Exception) {
                echo $Exception->getMessage();
            }
        }
        //This will select items from the database.
        else if($_POST['action'] == 'select'){

            $dbConn = connectToDatabase();
            $sql = 'SELECT ';
            $first = true;
            foreach($_POST['columns'] as $col){
                $sql .= (!$first ? ', ': '') . $col;
                $first = false;
            }
            $sql .= ' FROM spotter.' . $_POST['table'];

            if(isset($_POST['filter'])){
                $sql .= ' WHERE ';
                $filterFirst = true;

                //I feel like these need to be done better.
                foreach($_POST['filter'] as $key => $value){
                    $sql .= ($filterFirst ? '' : ' AND ') . $key . ' = \'' . $value . '\'';
                    $filterFirst = false;
                }
            }

            $stmt = $dbConn -> prepare($sql);
            try {
                $status = $stmt->execute();
                if($status){
                    echo json_encode($stmt->fetchall());
                } else {
                    echo "failure: " . $sql;
                }
            }
            catch (PDOException $Exception) {
                echo $Exception->getMessage();
            }
        }
    }



?>