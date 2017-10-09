<?php
require("../../../config.php");
$database = "if17_sarrpetr";

//alustan sessiooni
session_start();

//sisselogimise funktsioon
function signIn($email, $password){
	$notice = "";
	//serveriga ühendus
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, email, password FROM vpusers WHERE email = ?");
	$stmt->bind_param("s", $email);
	$stmt->bind_result($id, $emailFromDb, $passwordFromDb);
	$stmt->execute();
	
	//kontrollime vastavust
		if ($stmt->fetch()){
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb){
				$notice = "Logisite sisse!";
				
				//Määran sessiooni muutujad
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				
				//liigume edasi pealehele (main.php)
				header("Location: main.php");
				exit();
			} else {
				$notice = "Vale salasõna!";
			}
		} else {
			$notice = 'Sellise kasutajatunnusega "' .$email .'" pole registreeritud!';
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}


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
			echo "\n Tekkis viga!";
		}
		$stmt->close();
		$mysqli->close();
	}

	//mõtete salvestamine
	function saveIdea($idea, $color){
		//echo $color;
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpuserideas (userid, idea, ideacolor) VALUES (?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("iss", $_SESSION["userId"], $idea, $color);
		if($stmt->execute()){
			$notice = "Mõte on salvestatud";
		} else {
			$notice = "Mõtte salvestamisel tekkis viga." .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	//kõikide mõtete lugemise funktsioon
	function readAllIdeas(){
		$ideasHTML = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT idea, ideaColor FROM vpuserideas");
		$stmt->bind_result($idea, $color);
		$stmt->execute();
		$result = array();//?
		while ($stmt->fetch()){
			$ideasHTML .= '<p style="background-color: '.color .'">'.$idea ."</p> \n";
		}
		$stmt->close();
		$mysqli->close();
		return $ideasHTML;
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