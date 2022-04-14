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
            <a class="nav-link" href="/php/cashRegister/cashRegister.php"><i class="fa fa-fw fa-money"></i>Cash Register</a>
            </li>
            <li class="nav-item" id='inventory'>
            <a class="nav-link" href="/php/inventory/inventory.php"><i class="fa fa-fw fa-paperclip"></i>Inventory</a>
            </li>
            <li class="nav-item" id='transactions'>
            <a class="nav-link" href="/php/transactions/transactions.php"><i class="fa fa-fw fa-book"></i>Transactions</a>
            </li>
            <li class="nav-item" id='register'>
            <a class="nav-link" href="/php/registration/registration.php"><i class="fa fa-fw fa-pencil"></i>Registration</a>
            </li>
            <li class="nav-item dropdown" id='reports'>
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-fw fa-file"></i>Reports</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="/php/reporting/customersJoined.php">Customer Report</a></li>
            <li><a class="dropdown-item" href="/php/reporting/employeeSales.php">Employee Sales</a></li>
            <li><a class="dropdown-item" href="/php/reporting/topSeller.php">Top Sellers</a></li>
            </ul>
            <li class="nav-item" id='logout'>
            <a class="nav-link" href="/php/login/logout.php"></i>Log Out</a>
            
            </li> 
        </li>
        </ul>
        </nav>
</div>


<style>
 

.navbar {
    background: linear-gradient(135deg, #d66d75 , #e29587);
    width: 700px;
    border-radius: 10px;
    margin: auto;
    
    
}

body {
    background: linear-gradient(135deg, #ffafbd ,#ffc3a0); /*#91ac80    , (#a1c4fd , #c2e9fb) */
}

.nav-item:hover {
    background: linear-gradient(135deg, #ffafbd ,#ffc3a0);
    border-radius: 10px;
}

.dropdown-menu
{
    color: rgb(68, 65, 65);
    background: linear-gradient(135deg, #d66d75 , #e29587);
    border-radius: 10px;
}
.dropdown-item:hover
{
    background: linear-gradient(135deg, #ffafbd ,#ffc3a0);
    border-radius: 10px;
}

</style>
<?php 

    session_start();

    // Hides nav elements based on user
    if(!isset($_SESSION['MGR'])) {
        echo '<script>hide(\'reports\');</script>';
    }
    if(!isset($_SESSION['MGR']) && !isset($_SESSION['EMP'])) {
        echo '<script>hide(\'inventory\');</script>';
        echo '<script>hide(\'transactions\');</script>';
    }

?>
