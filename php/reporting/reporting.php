<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
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

// Selects Customers from DB based on their join date according to given parameters
function selectCustomer($start, $end) {
    try {
        $connection = openConnection();
        $selectQuery = 'SELECT * FROM Customers WHERE Start_DtTm >= \''.$start.'\' AND Start_DtTm <= \''.$end.'\'';
        $getCustomers = sqlsrv_query($connection, $selectQuery);
        if(!$getCustomers)
            die(print_r(sqlsrv_errors(), true));

        echo "<table border = '1' class='table'>
        <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Join Date</th>
        </tr>";      

        while($row = sqlsrv_fetch_array($getCustomers, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['FirstName'].'</td>';
            echo '<td>'.$row['LastName'].'</td>';
            echo '<td>'.$row['PhoneNumber'].'</td>';
            echo '<td>'.$row['Email'].'</td>';
            echo '<td>'.$row['Start_DtTm']->format('Y-m-d').'</td>';
            echo '</tr>';
        }

        sqlsrv_free_stmt($getCustomers);
        sqlsrv_close($connection);
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

// WIP
function selectEmployeeTransactions($start, $end) {
    try {
        $connection = openConnection();
        $selectQuery = 'SELECT EMP.EID, EMP.FirstName, EMP.LastName, SUM(INV.Price * TRI.Quantity) AS TotalSold FROM Employees EMP
                        INNER JOIN Transactions TRA ON TRA.ProcessedBy = EMP.EID
                        INNER JOIN TransactionItems TRI ON TRI.TransactionID = TRA.TransactionID
                        INNER JOIN Inventory INV ON INV.UPC = TRI.TransactionItemID
                        WHERE TRA.TypeOfTransaction = \'Purchase\' AND TRA.TransactionDate >= \''.$start.'\' AND TRA.TransactionDate <= \''.$end.'\' 
                        GROUP BY EMP.EID, EMP.FirstName, EMP.LastName';
        $getTransactions = sqlsrv_query($connection, $selectQuery);
        if(!$getTransactions)
            die(print_r(sqlsrv_errors(), true));
        
        echo "<table border = '1' class='table'>
        <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Total</th>
        </tr>";

        while($row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['EID'].'</td>';
            echo '<td>'.$row['FirstName'].'</td>';
            echo '<td>'.$row['LastName'].'</td>';
            echo '<td>$'.number_format($row['TotalSold'], 2).'</td>';
            echo '</tr>';
        }
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

?>

<!--Function to hide/show date range picker-->
<script type='text/javascript'>
    function toggleRange(selected) {
        if(selected.value =='customRange')
            document.getElementById('daterangepicker').style.display = 'block';
        else
            document.getElementById('daterangepicker').style.display = 'none';
    }

    function toggleTable() {
        document.getElementById('customertable').style.display = 'block';
    }
</script>
<!--END-->

<!--Customer query filters-->
<h1>Reporting Dashboard</h1>
<h3>Customers registered</h3>
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
    <br><br>
    <input type='submit' name='customerQuery' id='customerQuery' value='View Query' onsubmit='toggleTable()'>
</form>
<!--END-->

<div id='customertable'>
<?php

// Calls select function based on filter picked
if(array_key_exists('customerQuery', $_POST)) {
    $the_date = date('Y-m-d');
    $startDate = new DateTime;
    $endDate = new DateTime;
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
    echo $startDate.'~'.$endDate;
    selectCustomer($startDate, date('Y-m-d', strtotime($endDate)+60*60*24));
}

?>
</div>


<div id='employeetransactions'>
<h3>Employee Sales</h3>
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
    <br><br>
    <input type='submit' name='employeeSales' id='employeeSales' value='View Query' onsubmit='toggleTable()'>
</form>
<?php

if(array_key_exists('employeeSales', $_POST)) {
    $the_date = date('Y-m-d');
    $startDate = new DateTime;
    $endDate = new DateTime;
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
    echo $startDate.'~'.$endDate;
    selectEmployeeTransactions($startDate, date('Y-m-d', strtotime($endDate)+60*60*24));
}

?>
</div>