
<?php echo file_get_contents("html/header.html"); ?> 
<?php echo file_get_contents("html/body.html"); ?> 

<!-- user form  -->
<form method="post">
	<label for="bar">Name: </label>
	<input type="text" name="name"><br>
	<br><input type="submit"><br>

	<?= "Name inputted: " . $_POST["name"]; ?>
</form>


<?php echo file_get_contents("html/footer.html"); ?>