<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Kasutaja sisselogimine</title>
</head>
<body>
	<h1>Siin leheküljel saab registreerida kasutajaks ja sisse logida.</h1>
	
	
	<h2>Kasutajanimi</h2>
	<p>Palun sisesta oma kasutajanimi ehk e-posti aadress.</p>
	<form method="POST">
		<label>Teie kasutajanimi: </label>
		<p><input name="loginEmail" type="email"></p>
			<label>Teie parool: </label>
			<p><input name="loginPassword" type="password"></p>
			<input type="submit" value="Kinnita">
	</form>
		
	<h3>Uue kasutaja loomine</h3>
	<p>Uue kasutaja loomiseks tuleb sisestada ees- ja perekonnanimi, sugu, e-post ja parool.</p>
	<form method="POST">
		<label>Sisesta ees- ja perekonnanimi: </label>
		<p><input name="signupFirstName" type="text"></p>
		<p><input name="signupFamilyName" type="text"></p>
			<label>Sisesta sugu: 
			<p><input type="radio" name="gender" value="1"> Mees<br></p>
			<p><input type="radio" name="gender" value="2"> Naine<br></p>
				<label>Sisesta e-posti aadress: </label>
				<p><input name="signupEmail" tyle="email"></p>
					<label>Sisesta parool: </label>
					<p><input name="signupPassword" type="password"></p>
					<input type="submit" value="Kinnita">
	</form>
</body>
</html>