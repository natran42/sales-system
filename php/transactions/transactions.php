<?php include(__dir__.'/../main/nav.php'); ?>
<?php

function openConnection() {
$serverName = 'sevenseas.database.windows.net';
$connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
$connection = sqlsrv_connect($serverName, $connectionOptions);
if(!$connection)
die(print_r(sqlsrv_errors(), true));
return $connection;
}


//->Input customer transaction id
//->Pull up previous transaction info from transaction id

function fetchTransactions($userTransaction) {
    try {
        $connection = openConnection();
        $selectQuery = "SELECT * FROM Transactions WHERE TransactionID = '$userTransaction' ORDER BY TransactionDate DESC";
        $getTransactions = sqlsrv_query($connection, $selectQuery);
        if(!$getTransactions)
            die(print_r(sqlsrv_errors(), true));

        $selectSecondQuery = "SELECT TransactionItemID, Quantity FROM TransactionItems WHERE TransactionID = '$userTransaction'";
        $getTransactionItems = sqlsrv_query($connection, $selectSecondQuery);
        if(!$getTransactionItems)
            die(print-r(sqlsrv_errors(), true));
            

        echo "<table border = '1' class='table'>
        <tr>
        <th>Transaction ID</th>
        <th>Ordered By</th>
        <th>Type of Transaction</th>
        <th>Transaction Date</th>
        <th>Processed By</th>
        </tr>";   

        while($row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['TransactionID'].'</td>';
            echo '<td>'.$row['OrderedBy'].'</td>';
            echo '<td>'.$row['TypeOfTransaction'].'</td>';
            echo '<td>'.$row['TransactionDate']->format('Y-m-d').'</td>';
            echo '<td>'.$row['ProcessedBy'].'</td>';
            echo '</tr>';
        }

    
       
        echo "<table border = '1' class='table'>
        <tr>
        <th>Item ID</th>
        <th>Quantities</th>
        </tr>";   


        while($row = sqlsrv_fetch_array($getTransactionItems, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['TransactionItemID'].'</td>';
            echo '<td>'.$row['Quantity'].'</td>';
            echo '</tr>';
        }

        sqlsrv_free_stmt($getTransactions);
        sqlsrv_free_stmt($getTransactionItems);
        sqlsrv_close($connection);

    }
    catch(Exception $e) {
        echo 'Error';
    }
}
//Should we use UUID or Phone #?
?>

<h1>Customer Transactions</h1>

<form action = "transactions.php" method='post'>
    Enter Transaction ID: <input type="number" name="transID"/> <br/>
    <input type='submit' name='submit' id='submit' value='Submit'/>
</form>

<?php

if(!empty($_POST['submit'])){
    $userTransaction = $_POST['transID'];
    fetchTransactions(intval($userTransaction));
}


?>