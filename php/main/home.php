<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
	
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  background-color: beige;
  text-align: left;
}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: cornflowerblue;
  color: white;
}
</style>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- <div class="topnav">
  <a class="active" href="../main/home.php"><i class="fa fa-fw fa-home"></i>Home</a>
  <a href="../login/login.php"><i class="fa fa-fw fa-user"></i>Login</a>
  <a href="../cashRegister/cashRegister.php"><i class="fa fa-fw fa-money"></i>Cash Register</a>
  <a href="../inventory/inventory.php"><i class="fa fa-fw fa-paperclip"></i>Inventory</a>
  <a href = "../reporting/reporting.php"><i class="fa fa-fw fa-file"></i>Reporting</a>
  <a href =  "../registration/registration.php"><i class="fa fa-fw fa-pencil"></i>Registration </a>
</div> -->

<?php include(__dir__.'/../main/nav.php'); ?>

<div style="padding-center:16px">
  <h2>Welcome</h2>
</div>
</body>
</html>
