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


//->Input customer UUID
//->Pull up previous transaction info from UUID(From transaction table) 

function fetchTransactions($UUID) {
    try {
        $connection = openConnection();
        $selectQuery = 'SELECT * FROM Transactions WHERE OrderedBy = \''.$UUID.'\'ORDERED BY PurchaseDate DESC';
        $getTransactions = sqlsrv_query($connection, $selectQuery);
        if(!$getTransactions)
            die(print_r(sqlsrv_errors(), true));

        echo "<table border = '1'>
        <tr>
        <th>Transaction ID</th>
        <th>Ordered By</th>
        <th>Item Ordered</th>
        <th>Total Price</th>
        <th>Type of Transaction</th>
        <th>Purchase Date</th>
        </tr>";   

        while($row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['TransactionID'].'</td>';
            echo '<td>'.$row['OrderedBy'].'</td>';
            echo '<td>'.$row['ItemOrdered'].'</td>';
            echo '<td>'.$row['TotalPrice'].'</td>';
            echo '<td>'.$row['TypeOfTransaction'].'</td>';
            echo '<td>'.$row['PurchaseDate']->format('Y-m-d').'</td>';
            echo '</tr>';
        }

        sqlsrv_free_stmt($getTransactions);
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
    Enter Customer UUID: <input type="text" name="UUID" required="required" /> <br/>
    <input type='submit' name='getTransactions' id='getTransactions' value='Submit'/>
</form>

<?php

if(isset($_POST['submit'])){
    $userUUID = $_POST['UUID'];
    fetchTransactions($UserUUID);
}


?>