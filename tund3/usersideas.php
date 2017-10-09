<?php
	require("functions.php")
	
	//kui pole sisse loginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("location login.php");
		exit();
	}

	//kui logib välja
	if(isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("location login.php");
	}
	
	/*
	while($stmt->fetch()){
	
	}
	*/
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Petriku veebiprogemise asjad</title>
</head>
<body>
	<h1>Petrik</h1>
	<p>See veebileht on loodud veebiprogrammerimise kursusel ning ei sisalda tõsiselt võetavat sisu.</p>
	<p><a href="?logout=1">Logi välja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Lisa uus mõte</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Päeva esimene mõte: </label>
		<input name="idea" type="text">
		<br>
		<label>mõttega seostuv värv: </label>
		<input name="ideaColor" type="color">
		<br>
		<input name="ideaBtn" type="submit" value="salvesta">
		
	</form>
	
</body>
</html>