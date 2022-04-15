<?php include(__dir__.'/../main/nav.php'); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../reporting/reportingstyle.css">
</head>

<script>
    function toggleRange(selected) {
        if(selected.value == 'customRange')
            document.getElementById('daterangepicker').style.display = 'block';
        else
            document.getElementById('daterangepicker').style.display = 'none';
    }
</script>

<title>Customer Report</title>
    <form method='post'>
        <h3>Customers registered</h3>
        <select id='filter' name='filter' onchange='toggleRange(this)' class="form-select" aria-label="Default select example">
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
        <input type='submit' name='customerQuery' id='customerQuery' value='View Results'>
    </form>
    <!--END-->

<?php

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

        echo "<table border = '1' class='table table-hover'>
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
        if($_POST['filter'] === 'customRange' && ($_POST['startdaterange'] === '' || $_POST['enddaterange'] === '')) {
            echo '<p style=\'color:red\'>Please enter both a start and end date.</p>';
        }
        else {
            echo '<h3>'.$startDate.' ~ '.$endDate.'</h3>';
            selectCustomer($startDate, date('Y-m-d', strtotime($endDate)+60*60*24*1));
        }
    }

?>