
<?php include("php/main/header.php"); ?>
<?php include("php/main/body.php"); ?> 

<!-- user form  -->
<form method="post">
	<label for="bar">Name: </label>
	<input type="text" name="name"><br>
	<br><input type="submit"><br>

	<?= "Name inputted: " . $_POST["name"]; ?>
</form>

<?php include("php/main/footer.php"); ?>
