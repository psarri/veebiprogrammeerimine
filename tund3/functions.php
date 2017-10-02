<?php

$database = "if17_sarrpetr";

//kasutaja salvestamise funktsioon
function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $Gender, $signupEmail, $signupPassword){
	//loome andmebaasiühenduse
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//valmistame ette käsu andmebaasiserverile
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthday, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		//s - string
		//i - integer
		//d - detsimel
		$stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $Gender, $signupEmail, $signupPassword);
		//$stmt->execute()
		if ($stmt->execute()){
			echo "\n Õnnestus!";
		} else {
			echo "\n Tekkiv viga!";
		}
		$stmt->close();
		$mysqli->close();
	}

//sisestuse kontrollimise funktsioon
	function test_input($data){
		$data = trim($data);//ebavajalikud tühikud jms eemaldada
		$data = stripslashes($data);//kaldkriipsud jms eemaldada
		$data = htmlspecialchars($data);//muud märgid eemaldada
		return $data;
	}

	/*
	$x = 5;
	$y = 6;
	addValues();
	
	function addValues(){
		$z = $GLOBALS["x"] + $GLOBALS["y"];
		echo "Summa on: " .$z;
		$a = 3;
		$b = 4;
		echo "Teine summa on: " .($a + $b);
	}
	*/
?>