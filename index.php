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

<div class="topnav">
  <a class="active" href="php/main/home.php"><i class="fa fa-fw fa-home"></i>Home</a>
  <a href="php/login/login.php"><i class="fa fa-fw fa-user"></i>Login</a>
  <a href="php/cashRegister/cashRegister.php"><i class="fa fa-fw fa-money"></i>Cash Register</a>
  <a href="php/inventory/inventory.php"><i class="fa fa-fw fa-paperclip"></i>Inventory</a>
  <a href = "php/reporting/reporting.php"><i class="fa fa-fw fa-file"></i>Reporting</a>
  <a href =  "php/registration/registration.php"><i class="fa fa-fw fa-pencil"></i>Registration </a>
</div>


<div style="padding-center:16px">
  <h2>Welcome</h2>
</div>
</body>
</html>

	

<?php include("php/main/home.php"); ?>
<?php //include("php/main/connect.php"); ?> 

<!-- user form  -->
<!-- user form  
<form method="post">
	<label for="bar">Name: </label>
	<input type="text" name="name"><br>
	<br><input type="submit"><br>
</form>
-->

<?php include("php/login/login.php"); ?>
