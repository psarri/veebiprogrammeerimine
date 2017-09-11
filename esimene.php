<?php
	//Muutujad
	$myName = "Petrik";
	$myFamilyName = "Sarri";
	$practiceStarted = "2017-09-11 8.15";
	
	//echo strtotime($practiceStarted);
	//echo strtotime("now");
	//$timePassed = strtotime("now") - strtotime($practiceStarted);
	//echo $timePassed;
	
	$hourNow = date("H");
	$partOfDay = "";
	
	if ($hourNow < 8){
		$partOfDay = "varajane hommik";
	}
	if ($hourNow >= 8){
		$partOfDay = "koolipäev";
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Petrik veebiprogemise asjad</title>
</head>
<body>
	<h1>Petrik Sarri</h1>
	<p>See veebileht on loodud veebiprogrammerimise kursusel ning ei sisalda tõsiselt võetavat sisu.</p>

	<p>Tere tulemast minu veebilehele!</p>

	<?php
		echo "<p>Täna on vastik ilm.</p>";
		echo "<p>Täna on ";
		echo date("d.m.Y");
		echo ".</p>";
		echo "<p>Lehe laadimise hetkel oli kell " .date("h.i:s") ."</p>";
		echo "Praegu on " .$partOfDay .".";
	?>
	<p>PHP käivitatakse lehe laadimisel ja siis tehakse kogu töö ära. Hiljem, kui vaja midagi jälle
	midagi "kalkuleerida", siis laetakse kogu leht uuesti.</p>
	<?php
		echo "<p> Lehe autori täisnimi on: " .$myName ." " .$myFamilyName .".</p>";
	?>
</body>

</html>