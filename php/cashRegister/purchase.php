<?php
     function openConnection() {
        $serverName = 'sevenseas.database.windows.net';
        $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
        $connection = sqlsrv_connect($serverName, $connectionOptions);
        if(!$connection)
            die(print_r(sqlsrv_errors(), true));
        return $connection;
    }

    function getNextTransactionId($connection) {
        try {
            $selectQuery = 'SELECT MAX(TransactionID) AS TID FROM Transactions'; //greatest number being the last transactionID
            $getTID = sqlsrv_query($connection, $selectQuery);
            //$getTransactions = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
            if(!$getTID)
                die(print_r(sqlsrv_errors(), true));
            $row = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
            return $row['TID']+1; //incrementing row by 1 -> that being our next transactionID
        }
        catch(Exception $e) {
            echo 'Error';
        }
    }

    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database' => 'SalesSystemDB', 'UID' => 'admin7', 'PWD' => 'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if (!$connection)
        die(print_r(sqlsrv_errors(), true));


    if (isset($_GET['flush'])) {
        $transactionId = getNextTransactionId($connection); //manually put as 2 until we can fix the getNext function
        // get the manager/employee id from session table
        $query = "SELECT EID FROM Sessions";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die("Error getting employee"); 
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        $eid = $row['EID']; //employee or selfcheckout id
        // if you empty returned, set processby = null -> guest : EID
        if($result == null)
            die("No employee logged in");

        // grab UID for customer through phone number 
        $phoneNumber = $_GET['num'];
        $query = "SELECT UUID FROM Customers WHERE PhoneNumber = '$phoneNumber'";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die("Error getting customer");
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        $cid = isset($row['UUID']) ? $row['UUID'] : 100000; 
            
        // insert into transactions table
        $query = "INSERT INTO Transactions VALUES ($cid, 'Purchase', GETDATE(), $eid, $transactionId)";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die(print_r(sqlsrv_errors(), true));

        // connect transactionItem
        $query = "SELECT * FROM Cart";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die("Error in transactionItem");

        //cart items being put into transactionsItems table
        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $query2 = "INSERT INTO TransactionItems VALUES ($row[ItemID], $transactionId, $row[Quantity])";
            $result2 = sqlsrv_query($connection, $query2);
            if (!$result2)
                die("Error putting into transactionItems");
        }
    }

    // if the delete button is clicked we will delete the whole cart
    if(isset($_GET['flush']))
        $sqlquery = "DELETE FROM Cart";
    $result = sqlsrv_query($connection, $sqlquery);
    if($result)
        header('location:cashRegister.php');
    else
        die(print_r(sqlsrv_errors(), true));

?>
