<?php
	require("functions.php");
	$notice ="";
	
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
	
	//liidan klassi
	require("classes/Photoupload.class.php");
	//loome objekti
	/*$myphoto = new Photoupload("peidus");
	echo $myphoto->publicTest;
	echo $myphoto->privateTest;*/
	//$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"]), $imageFileType;
	
	
	//Algab fotode laadimise osa
	$target_dir = "../../pics/";
	$target_file;
	$uploadOk = 1;
	$imageFileType;
	$maxHeight = 400;
	$maxWidth = 600;
	$marginBottom = 10;
	$marginRight = 10;
	$visibility = "";
	
	// Kas on pildifail
	if(isset($_POST["submit"])) {
		
		//kas mingi fail valiti
		if(!empty($_FILES["fileToUpload"]["name"]));
		
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//tekitame failinime koos ajatempliga
			//$target_file = $target_dir .pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .(microtime(1) * 10000) ."." .$imageFileType;
			$target_file = "hmv_" .(microtime(1) * 10000) ."." .$imageFileType;
			//$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "Tegu on pildiga. - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "Tegu ei ole pildiga.";
			$uploadOk = 0;
		}
		// Kas fail on juba olemas
			if (file_exists($target_file)) {
				echo "Vabandage, fail on juba olemas.";
				$uploadOk = 0;
			}

		// Kontrolli failisuurust
			/*if ($_FILES["fileToUpload"]["size"] > 2000000) {
				echo "Vabandage, fail on liiga suur, et üles laadida.";
				$uploadOk = 0;
			}*/

			// Luba kindlad failiformaadid
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
				echo "Vabandage, ainult jpg, jpeg, png & gif on lubatud.";
				$uploadOk = 0;
			}
/*
		//Kas saab laadida?
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {		
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$notice .= "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
				} else {
					$notice .= "Vabandust, üleslaadimisel tekkis tõrge! ";
				}
			}
*/
		if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
		} else {

		//kasutan klassi
		$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
		$myPhoto->readExif();
		$myPhoto->resizeImage($maxHeight, $maxWidth);
		$myPhoto->addWatermark();
		//$myPhoto->addTextWatermark($myPhoto->exifToImage);
		$myPhoto->addTextWatermark("hmv_foto");
		$myPhoto->savePhoto($target_dir, $target_file);
		$myPhoto->clearImages();
		unset($myPhoto);
	}

	}//kas vajutati submit nuppu, lõppeb
	/*function resize_image($image, $origW, $origH, $w, $h){
		$dst = imagecreatetruecolor($w, $h);
		imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
		return $dst;
	}*/
	require("header.php")
?>

	<hr>
		<h2>Foto üleslaadimine</h2>
		<form action="photoupload.php" method="post" enctype="multipart/form-data">
			<label>Valige pildifail:</label>
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Lae üles" name="submit" id="submitPhoto"><span id="fileSizeError"></span>
			<br>
			<input type="radio" name="visibility" value="1" <?php if ($visibility == "1") {echo 'checked';} ?>><label>avalik</label>
			<input type="radio" name="visibility" value="2" <?php if ($visibility == "2") {echo 'checked';} ?>><label>sisseloginud kasutajale</label>
			<input type="radio" name="visibility" value="3" <?php if ($visibility == "3") {echo 'checked';} ?>><label>ainult omanikule</label>
		</form>
		
		<span><?php echo $notice; ?></span>

<?php
	echo '<script type="text/javascript" src="javascript/checkFileSize.js"></script>';
	require("footer.php")
?>