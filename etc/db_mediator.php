<?php

    /**************************************************************************

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
                    $sql .= ($filterFirst ? '' : ' AND ') . $value[0] . ' = :val' . $count;

                    //Add filter value for PDO execute()
                    $queryDetails[':val' . $count] = $value[1];

                    //Handle formatting
                    $filterFirst = false;
                }
            }

            //Prepare our database statement.
            $stmt = $dbConn -> prepare($sql);
            try {
                //Execute our query.
               /* if(isset($_POST['filter']) && $_POST['filter'][0][0] == 'location'){
                    print_r($queryDetails);
                    echo $sql . '<br /><br />';
                }*/
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




?>