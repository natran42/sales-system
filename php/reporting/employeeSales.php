<?php include(__dir__.'/../main/nav.php'); ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../reporting/reportingstyle.css">
</head>

<title>Employee Report</title>

<script>
    function toggleRange(selected) {
        if(selected.value == 'customRange')
            document.getElementById('daterangepicker').style.display = 'block';
        else
            document.getElementById('daterangepicker').style.display = 'none';
    }
</script>
            <form method='post'>
                <h3>Employee Sales</h3>
                <label>Employee Name: (Leave blank to get all employees)</label><input class="input-extra" type="text" name="empName"/>
                <select id='filter' name='filter' onchange='toggleRange(this)' class="form-select">
                    <option value='currWeek'>This week</option>
                    <option value='currMonth'>This month</option>
                    <option value='currYear'>This year</option>
                    <option value='customRange'>Custom Range</option>
                </select>
                <br>
                <div id='daterangepicker' style='display:none'>
                    <div class="input-group">
                    <span style="width:25%" class="input-group-text">Start date:</span>
                    <input class="form-control" type='date' id='startdaterange' name='startdaterange'>
                    </div>
                    <br>
                    <div class="input-group">
                    <span style="width:25%" class="input-group-text">End date:</span>
                    <input class="form-control" type='date' id='enddaterange' name='enddaterange'>
                    </div>
                </div>
                <br><br>
                <input type='submit' name='employeeSales' id='employeeSales' class='queryButton input-extra' value='View Results'>
            </form>
<?php

function openConnection() {
    $serverName = 'sevenseas.database.windows.net';
    $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
    $connection = sqlsrv_connect($serverName, $connectionOptions);
    if(!$connection)
        die(print_r(sqlsrv_errors(), true));
    return $connection;
}

// Selects Employees and sums up the total amount of transactions they have processed
function selectEmployeeTransactions($start, $end, $targetEmp) {
    try {
        $connection = openConnection();
        $selectQuery = ($targetEmp === '') ? 'SELECT EMP.EID, EMP.FirstName, EMP.LastName, SUM(INV.Price * TRI.Quantity) AS TotalSold, COUNT(TRA.TransactionID) AS TransactionsMade
                        FROM Employees EMP
                        INNER JOIN Transactions TRA ON TRA.ProcessedBy = EMP.EID
                        INNER JOIN TransactionItems TRI ON TRI.TransactionID = TRA.TransactionID
                        INNER JOIN Inventory INV ON INV.UPC = TRI.TransactionItemID
                        WHERE TRA.TypeOfTransaction = \'Purchase\' AND TRA.TransactionDate >= \''.$start.'\' AND TRA.TransactionDate <= \''.$end.'\' 
                        GROUP BY EMP.EID, EMP.FirstName, EMP.LastName' : 
                        'SELECT EMP.EID, EMP.FirstName, EMP.LastName, SUM(INV.Price * TRI.Quantity) AS TotalSold, COUNT(TRA.TransactionID) AS TransactionsMade
                        FROM Employees EMP
                        INNER JOIN Transactions TRA ON TRA.ProcessedBy = EMP.EID
                        INNER JOIN TransactionItems TRI ON TRI.TransactionID = TRA.TransactionID
                        INNER JOIN Inventory INV ON INV.UPC = TRI.TransactionItemID
                        WHERE (EMP.FirstName LIKE \''.$targetEmp.'\' OR EMP.LastName LIKE \''.$targetEmp.'\' OR EMP.FirstName + \' \' + EMP.LastName LIKE \''.$targetEmp.'\') AND TRA.TypeOfTransaction = \'Purchase\' AND TRA.TransactionDate >= \''.$start.'\' AND TRA.TransactionDate <= \''.$end.'\' 
                        GROUP BY EMP.EID, EMP.FirstName, EMP.LastName';
        $getTransactions = sqlsrv_query($connection, $selectQuery);
        if(!$getTransactions) {
            print($selectQuery);
            die(print_r(sqlsrv_errors(), true));
        }


        if($targetEmp !== '') {
            $row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC);
            if(empty($row))
                echo "<script>alert('No entries returned');</script>";
            else {
                echo "<br> <br>";
                echo "<table border = '1' class='table table-hover'>
                <tr>
                <th id=header colspan='5'>$start ~ $end</th>
                </tr>
                <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th># Transactions</th>
                <th>Total</th>
                </tr>";
                echo '<tr>';
                echo '<td>'.$row['EID'].'</td>';
                echo '<td>'.$row['FirstName'].'</td>';
                echo '<td>'.$row['LastName'].'</td>';
                echo '<td>'.$row['TransactionsMade'].'</td>';
                echo '<td>$'.number_format($row['TotalSold'], 2).'</td>';
                echo '</tr>';
            }
        }
        else {
            echo "<br> <br>";
            echo "<table border = '1' class='table table-hover'>
            <tr>
            <th id=header colspan='5'>$start ~ $end</th>
            </tr>
            <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th># Transactions</th>
            <th>Total</th>
            </tr>";
    
            while($row = sqlsrv_fetch_array($getTransactions, SQLSRV_FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>'.$row['EID'].'</td>';
                echo '<td>'.$row['FirstName'].'</td>';
                echo '<td>'.$row['LastName'].'</td>';
                echo '<td>'.$row['TransactionsMade'].'</td>';
                echo '<td>$'.number_format($row['TotalSold'], 2).'</td>';
                echo '</tr>';
            }
    
        }
    }
    catch(Exception $e) {
        echo 'Error';
    }
}

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
                if($_POST['filter'] === 'customRange' && ($_POST['startdaterange'] === '' || $_POST['enddaterange'] === '')) {
                    echo "<script>alert('Please enter both a start and end date.');</script>";
                }
                else {
                    isset($_POST['empName']) ? selectEmployeeTransactions($startDate, date('Y-m-d', strtotime($endDate)+60*60*24*1), $_POST['empName']) : selectEmployeeTransactions($startDate, date('Y-m-d', strtotime($endDate)+60*60*24*1), '');
                }
            }

?>