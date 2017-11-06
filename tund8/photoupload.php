<?php
	require("functions.php");
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}
	
	/*
	while($stmt->fetch()){
		
	}
	*/
?>
<!DOCTYPE html>
<html>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
    Vali pilt, mida soovid üles laadida:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Lae pilt üles" name="submit">
</form>

</body>
</html>