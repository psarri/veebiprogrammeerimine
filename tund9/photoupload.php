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
	
	$target_dir = "../../pics/";
	$target_file;
	$uploadOk = 1;
	$imageFileType;
	$maxHeight = 400;
	$maxWidth = 600;
	$marginBottom = 10;
	$marginRight = 10;
	
	// Kas on pildifail
	if(isset($_POST["submit"])) {
		
		//kas mingi fail valiti
		if(!empty($_FILES["fileToUpload"]["name"]));
		
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//tekitame failinime koos ajatempliga
			$target_file = $target_dir .pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .(microtime(1) * 10000) ."." .$imageFileType;
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
			if ($_FILES["fileToUpload"]["size"] > 2000000) {
				echo "Vabandage, fail on liiga suur, et üles laadida.";
				$uploadOk = 0;
			}

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
			
			//loeme EXIF infot, millal pilt tehti
			@$exif = exif_read_data($_FILES["fileToUpload"]["tmp_name"], "ANY_TAG", 0, true);
			//var_dump($exif);
			if(!empty($exif["DateTimeOriginal"])){
				$textToImage = "Pilt tehti: " .$exif["DateTimeOriginal"];
			} else {
				$textToImage = "Pildil puudub kuupäev.";
			}
			//lähtudes failitüübist, loon sobiva pildiobjekti
			if($imageFileType == "jpg" or $imageFileType == "jpeg"){
				$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
			}
			if($imageFileType == "gif"){
				$myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
			}
			if($imageFileType == "png"){
				$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
			}
		
		//suuruse muutmine
		//küsime originaalsuurust
		$imageWidth = imagesx($myTempImage);
		$imageHeight = imagesy($myTempImage);
		$sizeRatio = 1;
		if($imageWidth > $imageHeight){
			$sizeRatio = $imageWidth / $maxWidth;
		} else {
			$sizeRatio = $imageHeight / $maxHeight;
		}
		$myImage = resize_image($myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
		
		//vesimärgi lisamine
		$stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
		$stampWidth = imagesx($stamp);
		$stampHeight = imagesy($stamp);
		$stampPosX = round($imageWidth / $sizeRatio) - $stampWidth - $marginRight;
		$stampPosY = round($imageHeight / $sizeRatio) - $stampHeight - $marginBottom;
		imageCopy($myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
		
		//lisame ka teksti vesimärgile
		$textColor = imagecolorallocatealpha($myImage, 150, 150, 150, 50);
		//RGBA alpha 0-127
		imagettftext($myImage, 20, 0, 10, 25, $textColor, "../../graphics/ARIAL.TTF", $textToImage);
		
		//salvestame pildi
		if($imageFileType == "jpg" or $imageFileType == "jpeg"){
			if(imagejpeg($myImage, $target_file, 95)){
				$notice = "Fail: " .basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
			} else {
				$notice = "Vabandust, tekkis tõrge!";
			}
		}
		if($imageFileType == "png"){
			if(imagejpeg($myImage, $target_file, 95)){
				$notice = "Fail: " .basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
			} else {
				$notice = "Vabandust, tekkis tõrge!";
			}
		}
		if($imageFileType == "gif"){
			if(imagejpeg($myImage, $target_file, 95)){
				$notice = "Fail: " .basename( $_FILES["fileToUpload"]["name"]). " laeti üles! ";
			} else {
				$notice = "Vabandust, tekkis tõrge!";
			}
		}
		
		//mälu vabastamine
		imagedestroy($myImage);
		imagedestroy($myTempImage);
	}

	}//kas vajutati submit nuppu, lõppeb
	function resize_image($image, $origW, $origH, $w, $h){
		$dst = imagecreatetruecolor($w, $h);
		imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
		return $dst;
	}
	require("header.php")
?>

	<hr>
		<h2>Foto üleslaadimine</h2>
		<form action="photoupload.php" method="post" enctype="multipart/form-data">
			<label>Valige pildifail:</label>
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input type="submit" value="Lae üles" name="submit">
		</form>
		
		<span><?php echo $notice; ?></span>

<?php
	require("footer.php")
?>