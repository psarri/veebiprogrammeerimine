<?php
	require("../../../config.php");
	require("functions.php");
	//echo $serverHost;

	$signupFirstName = "";
	$signupFamilyName = "";
	$signupEmail = "";
	$gender = "";
	$signupBirthDay = null;
	$signupBirthMonth = null;
	$signupBirthYear = null;
	$signupBirthDate = "";
	
	$loginEmail = "";
	
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupBirthDayError = "";
	$signupBirthMonthError = "";
	$signupBirthYearError = "";
	$signupGenderError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	
	//kas on kasutajanimi sisestatud
	if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Ilma selleta ei saa sisse logida!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	//Kõiki kasutaja loomise sisestusi kontrollitakse vaid, kui on vastavat nuppu klikitud
	if(isset($_POST["signUpButton"])){
	
	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty ($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = test_input($_POST["signupFirstName"]);
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty ($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
		}
	}
	}
	//kas sünnikuupäev on sisestatud
	if (isset ($_POST["signupBirthDay"])){
		$signupBirthDay = $_POST["signupBirthDay"];
		//echo $signupBirthDay;
	}
	//kas sünniaasta on sisestatud
	if (isset ($_POST["signupBirthYear"])){
		$signupBirthYear = $_POST["signupBirthYear"];
		//echo $signupBirthYear;
	}
	//kas sünnikuu on sisestatud
	if (isset($_POST["signupBirthMonth"]) ){
		$signupBirthMonth = intval($_POST["signupBirthMonth"]);
	}
	//kui sünnikuu on sisestatud, siis kontrollime, kas on valiidne
	if (isset ($_POST["signupBirthDay"]) and isset ($_POST["signupBirthMonth"]) and isset ($_POST["signupBirthYear"])){
		if(checkdate (intval($_POST["signupBirthMonth"]), intval($_POST["signupBirthDay"]), intval($_POST["signupBirthYear"]) )){
			$BirthDate = date_create($_POST["signupBirthMonth"] ."/" .$_POST["signupBirthMonth"] ."/" .$_POST["signupBirthYear"]);
			$signupBirthDate = date_format($BirthDate, "Y.m.d");
		} else {
			$signupBirthDayError = "Viga sünnikuupäeva sisestamisel.";
		}
	}
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {
			//kutsun välja sisestuse kontrollimise funktsiooni
			$signupEmail = test_input($_POST["signupEmail"]);
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			if(!filter_var($signupEmail, FILTER_VALIDATE_EMAIL)){
				$signupEmailError ="NB! e-posti aadress pole nõutud kujul!";
			}
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
			$gender = intval($_POST["gender"]);
		} else {
			$signupGenderError = " (Palun vali sobiv!) Määramata!";
		}
	//UUE KASUTAJA ANDMEBAASI KIRJUTAMINE, kui kõik on olemas
	if (empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupBirthDayError) and empty($signupGenderError) and empty($signupEmailError) and empty($signupPasswordError)) {
		echo "hakkan salvestama";
		//krüpteerin parooli
		$signupPassword = hash("sha512", $_POST["$signupPassword"]);
		//kutsume välja kasutaja salvestamise funktsiooni
		signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
	}
		
	
	//loome kuupäeva valiku
	$signupDaySelectHTML = "";
	$signupDaySelectHTML .= '<select name="signupBirthDay"> \n';
	$signupDaySelectHTML .= '<option value="" selected disabled>päev</option> \n';
	for ($i = 1; $i < 32; $i ++){
		if($i == $signupBirthDay){
			$signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option> \n';
		} else {
			$signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option> \n';
		}
		
	}
	$signupDaySelectHTML.= "</select> \n";
	
	//Tekitame sünnikuu valiku
	$signupMonthSelectHTML = "";
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$signupMonthSelectHTML .= '<select name="signupBirthMonth";' ."\n";
	$signupMonthSelectHTML .= '<option> value="" selected disabled Vali sünnikuu</option>' ."\n";
	foreach ($monthNamesEt as $key=>$month){
		if ($key + 1 === $signupBirthMonth){
		$signupMonthSelectHTML .= '<option value="' .($key + 1) . '">'. $month . '</option>'  ."\n";
		} else {
		$signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month .'</option>' ."\n";
		}
	}
	$signupMonthSelectHTML .="</select> \n";
	
	//loome aasta valiku
	$signupYearSelectHTML = "";
	$signupYearSelectHTML .= '<select name="signupBirthYear"> \n';
	$signupYearSelectHTML .= '<option value="" selected disabled>aasta</option> \n';
	$yearNow = date("Y");
	for ($i = $yearNow; $i > 1900; $i --){
		if($i == $signupBirthYear){
			$signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option> \n';
		} else {
			$signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option> \n';
		}
		
	}
	$signupYearSelectHTML.= "</select> \n";
	
?>
<!DOCTYPE html>
<html lang="et">
<html>
<head>
	<meta charset="utf8">
	<title>Kasutaja sisselogimine</title>
</head>
<body>
	<h1>Siin leheküljel saab registreerida kasutajaks ja sisse logida.</h1>
	
	
	<h2>Kasutajanimi</h2>
	<p>Palun sisesta oma kasutajanimi ehk e-posti aadress.</p>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Teie kasutajanimi: </label>
		<p><input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>"></p>
			<label>Teie parool: </label>
			<p><input name="loginPassword" placeholder="Salasõna" type="password"></p>
			<input type="submit" value="Logi sisse">
	</form>
		
	<h3>Uue kasutaja loomine</h3>
	<p>Uue kasutaja loomiseks tuleb sisestada ees- ja perekonnanimi, sugu, e-post ja parool.</p>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Sisesta ees- ja perekonnanimi: </label>
		<p><input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
		<span><?php echo $signupFirstNameError ?></span></p>
		<p><input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
		<span><?php echo $signupFamilyNameError ?></span></p>
		<label>Sisesta oma sünnikuupäev</label>
		<?php
			echo "\n <br> \n" .$signupDaySelectHTML ."\n" .$signupMonthSelectHTML ."\n" .$signupYearSelectHTML ."\n <br> \n";
		?>
		<span><?php echo $signupBirthDayError; ?></span>
			<label>Sisesta sugu: 
			<p><input type="radio" name="gender" value="1" <?php if ($gender == "1") {echo 'checked';} ?>><label>Mees</label>></p>
			<p><input type="radio" name="gender" value="2" <?php if ($gender == "2") {echo 'checked';} ?>><label>Naine</label></p>
				<label>Sisesta e-posti aadress: </label>
				<p><input name="signupEmail" type="email" value="<?php echo $signupEmail; ?>"></p>
				<span><?php echo $signupEmailError; ?></span>
					<label>Sisesta parool: </label>
					<p><input name="signupPassword" placeholder="Salasõna" type="password"></p>
					<span><?php echo $signupPasswordError; ?></span>
					<input type="submit" value="Loo kasutaja">
	</form>
</body>
</html>