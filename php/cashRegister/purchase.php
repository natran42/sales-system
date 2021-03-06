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
    $pushTransaction = TRUE;


    if (isset($_GET['flush'])) {
        $query = "SELECT * FROM Cart";
        $result = sqlsrv_query($connection, $query);
        if (!$result)
            die("Error in transactionItem");
        $checkCart = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
        if(empty($checkCart)) {
            $pushTransaction = FALSE;
            header('location:cashRegister.php');
        }
        else {
            $transactionId = getNextTransactionId($connection);
            // get the manager/employee id from session table
            $query = "SELECT EID FROM Sessions";
            $result = sqlsrv_query($connection, $query);
            if (!$result)
                die("Error getting employee"); 
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            $eid = isset($row['EID']) ? $row['EID'] : 100000; 
            // if you empty returned, set processby = null -> guest : EID
    
            // grab UUID for customer through phone number 
            if(isset($_GET['num'])) {
                $phoneNumber = $_GET['num'];
                $query = "SELECT UUID FROM Customers WHERE PhoneNumber = '$phoneNumber'";
                $result = sqlsrv_query($connection, $query);
                if (!$result)
                    die("Error getting customer");
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
                $cid = isset($row['UUID']) ? $row['UUID'] : 100000; 
            }
            else {
                $cid = 100000;
            }
                
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
    
                // This code will update the inventory table with the SoldQty
                $updateQuery = "UPDATE Inventory SET SoldQty = SoldQty + $row[Quantity] WHERE UPC = $row[ItemID]";
                $updateItem = sqlsrv_query($connection, $updateQuery);
                if(!$updateItem)
                    die(print_r(sqlsrv_errors(), true));         
            }
        }   
    }

    // if the delete button is clicked we will delete the whole cart
    if($pushTransaction) {
        if(isset($_GET['flush'])){
            $sqlquery = "DELETE FROM Cart";
            $result = sqlsrv_query($connection, $sqlquery);
            if($result){
                header('location:confirmationpage.php');
            }
            else
                die(print_r(sqlsrv_errors(), true));
        }
    }
?>
