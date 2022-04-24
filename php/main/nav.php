<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
    function hide(e) {
        document.getElementById(e).style.display = 'none';
    }
    
    function show(e) {
        document.getElementById(e).style.display = 'block';
    }
    </script>
</head>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item" id='cashregister'>
            <a style = "color:white" class="nav-link" href="/php/cashRegister/cashRegister.php"><i class="fa fa-fw fa-money"></i> Cash Register</a>
            </li>
            <li class="nav-item" id='inventory'>
            <a style = "color:white" class="nav-link" href="/php/inventory/inventory.php"><i class="fa fa-fw fa-paperclip"></i>Inventory</a>
            </li>
            <li class="nav-item" id='transactions'>
            <a style = "color:white" class="nav-link" href="/php/transactions/transactions.php"><i class="fa fa-fw fa-book"></i>Transactions</a>
            </li>
            <li class="nav-item" id='register'>
            <a style = "color:white" class="nav-link" href="/php/registration/registration.php"><i class="fa fa-fw fa-pencil"></i>Registration</a>
            </li>
            <li class="nav-item" id='employees'>
            <a style = "color:white" class="nav-link" href="/php/employees/employees.php"><i class="fa fa-fw fa-user-o"></i>Employees</a>
            </li>
            <li class="nav-item dropdown" id='reports'>
                <a style = "color:white" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-file"></i>Reports</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a style = "color:white" class="dropdown-item" href="/php/reporting/customersJoined.php">Customers Registered</a></li>
            <li><a style = "color:white" class="dropdown-item" href="/php/reporting/employeeSales.php">Employee Sales</a></li>
            <li><a style = "color:white" class="dropdown-item" href="/php/reporting/topSeller.php">Top Sellers</a></li>
            </ul>
            <li class="nav-item" id='logout'>
            <a style = "color:white"  class="nav-link" href="/"><i class="fa fa-fw fa-sign-out"> </i>Log Out</a>
            </li> 
        </li>
        </ul>
        </nav>
</div>

<body>
    <div class = "box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    
    </div>
</body>


<style>
 

.navbar {
    background: linear-gradient(135deg,#4568dc , #b06ab3);
    box-shadow: 0px 0px 10px #000000;
    margin-bottom: 10px;
    /* padding: 0 15px; */
    height: 60px;
    font-size:large;
    height: 60px;
}

/* change the font color to white */

.navbar-nav {
    margin: auto;
    color: white;
    height:auto;
}

body {
    height: auto;
    background: linear-gradient(135deg, #000428  ,#004e92 ); /*#91ac80    , (#a1c4fd , #c2e9fb) */
    color: white;
}

.nav-item {
    padding: 9px 9px;
}

.nav-item:hover {
    background-color: #0652c5;
    background-image: linear-gradient(315deg, #0652c5 0%, #d4418e 74%);
    color: white;
}

.dropdown-menu
{
    color: rgb(68, 65, 65);
    background: linear-gradient(135deg,#4568dc , #b06ab3);
    border-radius: 10px;
    
}
.dropdown-item:hover
{
    background: linear-gradient(135deg, #ffafbd ,#ffc3a0);
    background-color: #0652c5;
    background-image: linear-gradient(315deg, #0652c5 0%, #d4418e 74%);
    
}
.box div{
    position: fixed;
    width: 60px;
    height: 60px;
    background-color: transparent;
    border: 6px solid rgba(255,255,255,0.8);
    border-radius: 50%;
}

.box div:nth-child(1){
    top: 12%;
    left: 42%;
    animation: animate 10s linear infinite;
}

.box div:nth-child(2){
    top: 70%;
    left: 50%;
    animation: animate 7s linear infinite;
}

.box div:nth-child(3){
    top: 17%;
    left: 6%;
    animation: animate 9s linear infinite;
}

.box div:nth-child(4){
    top: 20%;
    left: 60%;
    animation: animate 10s linear infinite;
}

.box div:nth-child(5){
    top: 67%;
    left: 10%;
    animation: animate 6s linear infinite;
}

.box div:nth-child(6){
    top: 80%;
    left: 70%;
    animation: animate 12s linear infinite;
}

.box div:nth-child(7){
    top: 60%;
    left: 80%;
    animation: animate 15s linear infinite;
}

.box div:nth-child(8){
    top: 32%;
    left: 25%;
    animation: animate 16s linear infinite;
}

.box div:nth-child(9){
    top: 90%;
    left: 25%;
    animation: animate 9s linear infinite;
}

.box div:nth-child(10){
    top: 20%;
    left: 80%;
    animation: animate 5s linear infinite;
}




@keyframes animate {
    0%{
        transform: scale(0) translateY(0) rotate(0);
        opacity: 1;
    }
    100%{
        transform: scale(1.3) translateY(-90px) rotate(360deg);
        opacity: 0;
    }
}









</style>
<?php 

    function openAuthConnection() {
        $serverName = 'sevenseas.database.windows.net';
        $connectionOptions = array('Database'=>'SalesSystemDB', 'UID'=>'admin7', 'PWD'=>'TeamSeven7');
        $connection = sqlsrv_connect($serverName, $connectionOptions);
        if(!$connection)
            die(print_r(sqlsrv_errors(), true));
        return $connection;
    }

    function getAuth() {
        try {
            $connection = openAuthConnection();
            $sql = 'SELECT * FROM Sessions';
            $getUser = sqlsrv_query($connection, $sql);;
            if(!$getUser)
                die(print_r(sqlsrv_errors(), true));
    
            $row = sqlsrv_fetch_array($getUser);
            if(empty($row))
                return 'CUST';

            $mgr = $row['MGR'];
            $emp = $row['EMP'];
    
            sqlsrv_free_stmt($getUser);
            sqlsrv_close($connection);
    
            return $mgr === 1 ? 'MGR' : 'EMP';
        }
        catch(Exception $e) {
            echo 'Error';
        }
    }

    $accessLevel = getAuth();

    if($accessLevel !== 'MGR') {
        echo '<script>hide(\'reports\');</script>';
        echo '<script>hide(\'employees\');</script>';
    }
    if($accessLevel !== 'MGR' && $accessLevel !== 'EMP') {
        echo '<script>hide(\'inventory\');</script>';
        echo '<script>hide(\'transactions\');</script>';
    }

    // Hides nav elements based on user
    // if(!isset($_SESSION['MGR'])) {
    //     echo '<script>hide(\'reports\');</script>';
    // }
    // if(!isset($_SESSION['MGR']) && !isset($_SESSION['EMP'])) {
    //     echo '<script>hide(\'inventory\');</script>';
    //     echo '<script>hide(\'transactions\');</script>';
    // }
    

?>