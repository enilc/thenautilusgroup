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
        } else if(strtolower($_POST['action']) == 'insert'){
            //Data being added to our database. Will be used for PDO execute()
            $queryDetails = array();

            //Connect to SQL database.
            $dbConn = connectToDatabase();

            //Check our posted table against the tables present within spotter.
            if (!in_array($_POST['table'],getTableNames('spotter'))){
                echo 'Invalid Table Name';
                exit(1);
            }

            //Since we know $_POST['table'] is safe, add directlyt to query string.
            $sql = 'INSERT INTO spotter.`' . $_POST['table'] . '` (';

            //formatting flag
            $first = true;

            //get a list of column names for the table we're inserting into
            $colNames = getColumnNames($_POST['table']);
            foreach($_POST['columns'] as $col){
                //Abort if any column is not a direct match to what getColumnNames() found.
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
            foreach($_POST['values'] as $val){

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

        //This will select items from the database.
        else if(strtolower($_POST['action']) == 'select'){
            //Connect to SQL database.
            $dbConn = connectToDatabase();

            //Check our posted table against the tables present within spotter.
            if (!in_array($_POST['table'],getTableNames('spotter'))){
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
            $columns = getColumnNames($_POST['table']);
            foreach($_POST['columns'] as $col){
                //Check to make sure our POSTed column name is valid
                if(!in_array($col, $columns)){
                    echo "Invalid Column Names";
                    exit(1);
                }

                //Since we know $col is safe, we add directly to $sql string.
                $sql .= (!$first ? ', ': '') . $col;

                //Allow for formtting.
                $first = false;
            }

            //Since we know our POSTed table name is valid, add directly to sql string.
            $sql .= ' FROM spotter.`' . $_POST['table'] . '`';

            //Add filters if present.
            if(isset($_POST['filter'])){
                //Add our filter condition to the sql query
                $sql .= ' WHERE ';

                //formatting flag and counter
                $filterFirst = true;
                $count = 0;

                foreach($_POST['filter'] as $key => $value){
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
    }


function getColumnNames($table){
    //Credit: https://stackoverflow.com/questions/5428262/php-pdo-get-the-columns-name-of-a-table
    $dbConn = connectToDatabase();
    $stmt = $dbConn->prepare('DESCRIBE spotter.`' . $table . '`');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
function getTableNames($db){
    //Credit: https://stackoverflow.com/questions/5428262/php-pdo-get-the-columns-name-of-a-table
    $dbConn = connectToDatabase();
    $stmt = $dbConn->prepare('SELECT table_name FROM information_schema.tables WHERE table_schema=:db;');
    $stmt->execute(array(':db' => $db));
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

?>