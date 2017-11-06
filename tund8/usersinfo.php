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
	<h2>Kõik süsteemi kasutajad</h2>
	<table border="1" style="border: 1px solid black; border-collapse: collapse">
	<tr>
		<th>Eesnimi</th><th>Perekonnanimi</th><th>e.posti aadress</th>
	</tr>
	<tr>
		<td>Mati</td><td>Kask</td><td>mati@eesti.ee</td>
	</tr>
	<tr>
		<td>Jaanus</td><td>Tuulik</td><td>jaanus@hot.ee</td>
	</tr>
	<tr>
		<td>Petrik</td><td>Sarri</td><td>petrik@eesti.ee</td>
	</table>
	
</body>
</html>