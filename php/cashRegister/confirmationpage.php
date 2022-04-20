<?php

function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

function getTransactionId($connection) {
    try {
        $selectQuery = 'SELECT MAX(TransactionID) AS TID FROM Transactions'; //greatest number being the last transactionID
        $getTID = sqlsrv_query($connection, $selectQuery);
        //$getTransactions = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
        if(!$getTID)
            die(print_r(sqlsrv_errors(), true));
        $row = sqlsrv_fetch_array($getTID, SQLSRV_FETCH_ASSOC);
        return $row['TID']; //incrementing row by 1 -> that being our next transactionID
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

$connection = openConnection();

?>

<html>
    <head>
        <title>Confirmation</title>
        <link rel="stylesheet" href="confirmation.css">
    </head>

    <body>
        <div class="main-text">
            <h1>Thank you for your purchase!</h1>
            <p>Your transaction ID is <?php echo getTransactionId($connection); ?></p>
        </div>
    </body>
</html>

