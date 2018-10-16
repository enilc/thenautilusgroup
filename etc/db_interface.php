<?php
    /**************************************************************************
    db_interface.php will allow INSERT and SELECT operations to be performed
    on the database with a HTTP POST request.

    Accepted format for SELECT:

        $_POST = (
            'key' => 'B52C106C63CB00C850584523FB0EC12',
            'action' => 'select',
            'columns' => array('loc_name', latitude, ...), //The names of database columns to select
            'filter' => array('loc_name' => 'Toronto') //Applies "WHERE key = value" filter to end of SQL Statement.
        )
    Accepted format for INSERT:

        $_POST = (
            'key' => 'B52C106C63CB00C850584523FB0EC12',
            'action' => 'insert',
            'columns' => array('loc_name', latitude, ...), //The names of database columns to INSERT
            'values' => array('Toronto', 0.00, ...) //an array of values that corresponds to column names.
        )

    Responses are printed as JSON format objects.

    /**************************************************************************/


//Mediator is preferred database connection object.
require_once('db_mediator.php');


function postInterface($dbMed){
    //Shim to use for javascript. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
        $_POST = json_decode(file_get_contents('php://input'), true);

    //First test is to just see if we can connect to db_interface.php. Tells angular connection worked
    if (isset($_POST['test'])){
        $dbMed -> conTestHandler($_POST['test']);
    } else if(strtolower($_POST['action']) == 'userlogin') {
        //Kill database connection for blank attempts
        if (!(isset($_POST['email']) && isset($_POST['password']))){
            echo '0';
            exit(1);
        }

        //authenticate user
        $dbMed -> userLogin($_POST['email'],$_POST['password']);
    }
    //This test ensures that all post requests use a key. Step is only effective against web bots.
    else if ($_POST['key'] == 'B52C106C63CB00C850584523FB0EC12') { 

        switch (strtolower($_POST['action'])){
            case 'key_test': //Just testing to see if we can get the interface to see the key posted
                echo 'Key Test Passed';
                break;
            case 'insert': //Insert data into the database
                $dbMed -> dbInsert($_POST['table'], $_POST['columns'], $_POST['values']);
                break;
            case 'select': //Select data from the database
                $dbMed -> dbSelect($_POST['table'],$_POST['columns'],(isset($_POST['filter']) ? $_POST['filter'] : NULL));
                break;
            case 'clean_test': //Cleanup files used in the database testing page
                $dbMed -> dbTestCleanup($_POST['filter']);
                break;
            case 'user_exist': //Simple boolean check to see if a user exists in the User table.
                $dbMed -> dbUserExist($_POST['email']);
                break;
            case 'verify_email': //Move a potential user from the registration Queue to the User table.
                $dbMed -> dbValidateEmail((isset($_POST['email']) ? $_POST['email'] : NULL),
                                            (isset($_POST['token']) ? $_POST['token'] : NULL));
                break;
            default:
                break;
        }

    }
}

//Create a database mediator
$dbInterfaceMediator = new Mediator;

//Execute POSTED request.
postInterface($dbInterfaceMediator);

?>