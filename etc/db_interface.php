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

    **************************************************************************

    Mediator Object:

    To interface with the MySQL database I've (Caleb Allen) used the Mediator
    design pattern.

    ___________________         _____________________         _________________
    | Javascript POST |         | Mediator Class    |         | MySQL Database|
    | to              | <-----> |-------------------| <-----> |---------------|
    | db_interface.php|         | dbTestCleanup()   |         | User Data     |
    |                 |         | dbSelect()        |         | Location Data |
    -------------------         | dbInsert()        |         | Picture Data  |
                                | userLogin()       |         -----------------
                                | conTestHandler()  |
                                | getColumnNames()  |
                                | getTableNames()   |  
                                ---------------------
    /**************************************************************************/

    require_once('db_connect.php');
    require_once('user_funct.php');
    require_once('db_mediator.php');


function postInterface($dbMed){
    //Shim to use for interaction. Credit: https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
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
    //This test makes sure that the 'key' can be used to access the page. Better than nothing, but not much.
    else if ($_POST['key'] == 'B52C106C63CB00C850584523FB0EC12') { 

        switch (strtolower($_POST['action'])){
            case 'key_test':
                echo 'Key Test Passed';
                break;
            case 'insert':
                $dbMed -> dbInsert($_POST['table'], $_POST['columns'], $_POST['values']);
                break;
            case 'select':
                $dbMed -> dbSelect($_POST['table'],$_POST['columns'],(isset($_POST['filter']) ? $_POST['filter'] : NULL));
                break;
            case 'clean_test':
                $dbMed -> dbTestCleanup($_POST['filter']);
                break;
            case 'user_exist':
                $dbMed -> dbUserExist($_POST['email']);
                break;
            case 'verify_email':
                $dbMed -> dbValidateEmail((isset($_POST['email']) ? $_POST['email'] : NULL),
                                            (isset($_POST['token']) ? $_POST['token'] : NULL));
                break;
            default:
                break;
        }

    }
}


$dbInterfaceMediator = new Mediator;
postInterface($dbInterfaceMediator);

?>