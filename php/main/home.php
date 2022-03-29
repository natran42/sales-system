<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  background-color: beige;
  text-align: center;

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

<div class="topnav">
  <a class="active" href="#home">Login</a>
  <a href="php/cashRegister/cashRegister.php">Cash Register</a>
  <a href="php/inventory/inventory.php">Inventory</a>
  <a href = "php/reporting/reporting.php">Reporting</a>
</div>

<div style="padding-left:16px">
  <h2>Login ....</h2>
  <p></p>
</div>

</body>
</html>
