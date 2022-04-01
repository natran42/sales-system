<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<?php 


// Selects top 10 items sold from the Inventory table
function selectTopSellers() {
    try {
        $connection = openConnection();
        $selectQuery = 'SELECT TOP 10 INV.Name, INV.Size, INV.SoldQty, INV.Price * INV.SoldQty AS GrossSales
                        FROM Inventory INV
                        ORDER BY SoldQty DESC';
        $getTopSellers = sqlsrv_query($connection, $selectQuery);
        if(!$getTopSellers)
            die(print_r(sqlsrv_errors(), true));
        
        echo "<table border = '1' class='table'>
        <tr>
        <th>Item Name</th>
        <th>Size</th>
        <th># Sold</th>
        <th>Gross Sales</th>
        </tr>";

        while($row = sqlsrv_fetch_array($getTopSellers, SQLSRV_FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>'.$row['Name'].'</td>';
            echo '<td>'.$row['Size'].'</td>';
            echo '<td>'.$row['SoldQty'].'</td>';
            echo '<td>$'.number_format($row['GrossSales'], 2).'</td>';
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
</script>
<!--END-->

<h1>Reporting Dashboard</h1>

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-customersjoined-tab" data-bs-toggle="tab" data-bs-target="#nav-customersjoined" type="button" role="tab" aria-controls="nav-customersjoined" aria-selected="true">Customers Joined</button>
    <button class="nav-link" id="nav-employeesales-tab" data-bs-toggle="tab" data-bs-target="#nav-employeesales" type="button" role="tab" aria-controls="nav-employeesales" aria-selected="false">Employee Sales</button>
    <button class="nav-link" id="nav-topsellers-tab" data-bs-toggle="tab" data-bs-target="#nav-topsellers" type="button" role="tab" aria-controls="nav-topsellers" aria-selected="false">Top Sellers</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-customersjoined" role="tabpanel" aria-labelledby="nav-customersjoined-tab"><?php include('customersJoined.php') ?></div>
  <div class="tab-pane fade" id="nav-employeesales" role="tabpanel" aria-labelledby="nav-employeesales-tab"><?php include('employeeSales.php') ?></div>
  <div class="tab-pane fade" id="nav-topsellers" role="tabpanel" aria-labelledby="nav-topsellers-tab"><?php include('topSeller.php') ?></div>
</div>




