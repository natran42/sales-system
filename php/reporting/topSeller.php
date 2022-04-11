<?php include(__dir__.'/../main/nav.php'); ?>

<script>
    function toggleRange(selected) {
        if(selected.value == 'customRange')
            document.getElementById('daterangepicker').style.display = 'block';
        else
            document.getElementById('daterangepicker').style.display = 'none';
    }
</script>

<title>Top Sellers</title>
<h3>Top 10 Sellers</h3>
<form method='post'>
    <select id='filter' name='filter' onchange='toggleRange(this)'>
        <option value='currWeek'>This week</option>
        <option value='currMonth'>This month</option>
        <option value='currYear'>This year</option>
        <option value='customRange'>Custom Range</option>
    </select>
    <div id='daterangepicker' style='display:none'>
        <p>Start date:</p>
        <input type='date' id='startdaterange' name='startdaterange'>
        <p>End date:</p>
        <input type='date' id='enddaterange' name='enddaterange'>
    </div>
    <select id='order' name='order'>
        <option value='qty'>Qty Sold</option>
        <option value='gross'>Grossing</option>
    </select>
    <br><br>
    <input type='submit' name='topSellers' id='topSellers' value='View Results'>
</form>

<?php
// Opens up a connection to the DB
function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

// Selects top 10 items sold from the Inventory table
function selectTopSellers($start, $end, $order) {
    try {
        $connection = openConnection();
        $selectQuery = 'SELECT TOP 10 INV.Name, INV.Size, SUM(TRI.Quantity) AS NSold, SUM(INV.Price * TRI.Quantity) AS GrossSales FROM Inventory INV
                        INNER JOIN TransactionItems TRI ON INV.UPC = TRI.TransactionItemID
                        INNER JOIN Transactions TRA ON TRI.TransactionID = TRA.TransactionID
                        WHERE TRA.TypeOfTransaction = \'Purchase\' AND TRA.TransactionDate >= \''.$start.'\' AND TRA.TransactionDate <= \''.$end.'\'
                        GROUP BY INV.Name, INV.Size, INV.SoldQty
                        ORDER BY '.$order.' DESC';
        $getTopSellers = sqlsrv_query($connection, $selectQuery);
        if(!$getTopSellers)
            die(print_r(sqlsrv_errors(), true));

        echo "<table border = '1' class='table table-hover'>
        <tr>
        <th>#</th>
        <th>Item Name</th>
        <th>Size</th>
        <th># Sold</th>
        <th>Gross Sales</th>
        </tr>";
        $count = 1;

        while($row = sqlsrv_fetch_array($getTopSellers, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$count++.'</td>';
            echo '<td>'.$row['Name'].'</td>';
            echo '<td>'.$row['Size'].'</td>';
            echo '<td>'.$row['NSold'].'</td>';
            echo '<td>$'.number_format($row['GrossSales'], 2).'</td>';
            echo '</tr>';
        }
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

if(array_key_exists('topSellers', $_POST)) {
    $the_date = date('Y-m-d');
    $startDate = new DateTime;
    $endDate = new DateTime;
    $order = $_POST['order'] === 'qty' ? 'NSold' : 'GrossSales';
    echo '<script>console.log(\''.$order.'\');</script>';
    switch($_POST['filter']) {
        case 'currWeek':
            $the_day_of_week = date('w', strtotime($the_date));
            $startDate = date('Y-m-d', strtotime( $the_date )-60*60*24*($the_day_of_week)+60*60*24*1);
            $endDate = date('Y-m-d', strtotime($startDate)+60*60*24*6);
            
            break;
        case 'currMonth':
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
            break;

        case 'currYear':
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-t');
            break;

        case 'customRange':
            $startDate = $_POST['startdaterange'];
            $endDate = $_POST['enddaterange'];
            break;

        default:
            break;
    }
    if($_POST['filter'] === 'customRange' && ($_POST['startdaterange'] === '' || $_POST['enddaterange'] === '')) {
        echo '<p style=\'color:red\'>Please enter both a start and end date.</p>';
    }
    else {
        echo '<h3>'.$startDate.' ~ '.$endDate.'</h3>';
        selectTopSellers($startDate, date('Y-m-d', strtotime($endDate)+60*60*24*1), $order);
    }
}
?>