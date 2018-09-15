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

    class Mediator {
        //Function for deleting locations added during testing
        function dbTestCleanup($filter){
            $dbConn = connectToDatabase();
            $sql = "DELETE FROM spotter.Location WHERE loc_name = :testString";
            $stmt = $dbConn -> prepare($sql);
            if(!$stmt -> execute(array(':testString' => $filter))){
                echo 'FAILURE of Cleanup';
            } else {
                echo 'success';
            }
        }

        //Function for selecting data from the database
        function dbSelect($table,$columns,$filter){
            //Connect to SQL database.
            $dbConn = connectToDatabase();

            //Check our posted table against the tables present within spotter.
            if (!in_array($table,$this -> getTableNames('spotter'))){
                echo 'Invalid Table Name';
                exit(1);
            }

            //Initialize our sql query string.
            $sql = 'SELECT ';

            //Initialize our PDO Execute() array
            $queryDetails = array();

            //Formatting flag
            $first = true;

            //Get list of valid columns from our table.
            $validColumns = $this -> getColumnNames($table);
            foreach($columns as $col){
                //Check to make sure our POSTed column name is valid
                if(!in_array($col, $validColumns)){
                    echo "Invalid Column Names";
                    exit(1);
                }

                //Since we know $col is safe, we add directly to $sql string.
                $sql .= (!$first ? ', ': '') . $col;

                //Allow for formtting.
                $first = false;
            }

            //Since we know our POSTed table name is valid, add directly to sql string.
            $sql .= ' FROM spotter.`' . $table . '`';

            //Add filters if present.
            if(!is_null($filter)){
                //Add our filter condition to the sql query
                $sql .= ' WHERE ';

                //formatting flag and counter
                $filterFirst = true;
                $count = 0;

                foreach($filter as $key => $value){
                    //Check to make sure our POSTed column name is valid.
                    if(!in_array($key,$columns)){
                        echo 'invalid filter key';
                        exit(1);
                    }

                    //Since we know our column name is save, add directly to the SQL key.
                    //Also add placemarker or filter value.
                    $sql .= ($filterFirst ? '' : ' AND ') . $key . ' = :val' . $count;

                    //Add filter value for PDO execute()
                    $queryDetails[':val' . $count] = $value;

                    //Handle formatting
                    $filterFirst = false;
                }
            }

            //Prepare our database statement.
            $stmt = $dbConn -> prepare($sql);
            try {
                //Execute our query.
                $status = $stmt->execute($queryDetails);
                if($status){

                    //Print results as JSON if successful.
                    echo json_encode($stmt->fetchall());
                } else {
                    echo "failure: " . $sql;
                }
            }
            catch (PDOException $Exception) {
                echo $Exception->getMessage();
            }
        }

        //Function for Inserting data into the database
        function dbInsert($table, $columns, $values){
            //Data being added to our database. Will be used for PDO execute()
            $queryDetails = array();

            //Connect to SQL database.
            $dbConn = connectToDatabase();

            //Check our posted table against the tables present within spotter.
            if (!in_array($table,$this -> getTableNames('spotter'))){
                echo 'Invalid Table Name';
                exit(1);
            }

            //Since we know $table is safe, add directlyt to query string.
            $sql = 'INSERT INTO spotter.`' . $table . '` (';

            //formatting flag
            $first = true;

            //get a list of column names for the table we're inserting into
            $colNames = $this -> getColumnNames($table);
            foreach($columns as $col){
                //Abort if any column is not a direct match to what $this -> getColumnNames() found.
                if (!in_array($col,$colNames)){
                    echo 'Invalid Column Names';
                    exit(1);
                }

                //Since we know $col is safe, add directly to the query string.
                $sql .= (!$first ? ', ': '') . $col;
                $first = false;
            }

            $sql .= ') VALUES (';

            //Formatting flag
            $first = true;

            //Column name counter
            $count = 0;
            foreach($values as $val){

                //Add placemarkers to SQL String
                $sql .= (!$first ? ', ': '') . ':val' . $count;

                //Add values for later translation by PDO execute()
                $queryDetails[':val' . $count] = $val;

                //Formatting trackers
                $first = false;
                $count += 1;
            }
            $sql .= ')';


            //Prepare our statement
            $stmt = $dbConn -> prepare($sql);

            //In try block incase something goes wrong.
            try {
                //Execute query with column names processed by PDO
                $status = $stmt->execute($queryDetails);
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

        //Handle User Login/Authentication
        function userLogin($username,$password){
            if(authenticateUser($username,$password)){
                echo '1';
            } else {
                echo '0';
            }
        }

        //Echo back received data during connection testing
        function conTestHandler($data){
            echo $data;
        }
        function dbValidateEmail($email, $token){
            if($email == NULL || $token == NULL){
                echo '0';
                return 1;
            }
            //Connect to SQL database.
            $dbConn = connectToDatabase();

            $sql = "SELECT email, token FROM spotter.Queue WHERE email = :email AND token = :token";
            $stmt = $dbConn -> prepare($sql);

            if(!$stmt -> execute(array(':email' => strtolower($email), ':token' => $token))){
                echo '0';
            } else {
                if($stmt -> rowCount() > 0){
                    echo '1';
                } else {
                    echo '0';
                }

            }
        }

        //List valid column names for a specific table
        function getColumnNames($table){
            //Credit: https://stackoverflow.com/questions/5428262/php-pdo-get-the-columns-name-of-a-table
            $dbConn = connectToDatabase();
            $stmt = $dbConn->prepare('DESCRIBE spotter.`' . $table . '`');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        //List validTableNames for a specific database.
        function getTableNames($db){
            //Credit: https://stackoverflow.com/questions/5428262/php-pdo-get-the-columns-name-of-a-table
            $dbConn = connectToDatabase();
            $stmt = $dbConn->prepare('SELECT table_name FROM information_schema.tables WHERE table_schema=:db;');
            $stmt->execute(array(':db' => $db));
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        //returns '1' if a user exists in the User database, returns 0 otherwise.
        function dbUserExist($email){

            $dbConn = connectToDatabase();
            $sql = 'SELECT * FROM spotter.User WHERE email = :email';
            $stmt = $dbConn->prepare($sql);
            $stmt->execute(array(':email' => $email));
            $results = $stmt->fetchAll();
            if(count($results) > 0){
                echo "1";
            } else {
                echo "0";
            }
        }
    }



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