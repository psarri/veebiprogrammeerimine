<?php
	class Photoupload {
		/*public $publicTest;
		private $privateTest;*/
		private $tempName;
		private $imageFileType;
		private $textToImage;
		public $exifToImage;
		private $myTempImage;
		private $myImage;
		private $marginRight;
		private $marginBottom;
		
		function __construct($fileToUpload, $fileType){
			/*$this->publicTest = "täitsa avalik";
			$this->privateTest = $x;*/
			$this->tempName = $fileToUpload;
			$this->imageFiletype = $fileType;
			$this->marginRight = 10;
			$this->marginBottom = 10;
		}
		
		public function readExif(){
			//loeme EXIF infot, millal pilt tehti
			@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
			//var_dump($exif);
			if(!empty($exif["DateTimeOriginal"])){
				$this->textToImage = "Pilt tehti: " .$exif["DateTimeOriginal"];
			} else {
				$this->exifToImage = "Pildil puudub kuupäev.";
			}
		}
		
		private function createImage(){
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
			}
			if($this->imageFileType == "png"){
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
		}
		
		public function resizeImage($width, $height){
			$this->createImage();
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			$sizeRatio = 1;
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			$this->myImage = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));
		}
		
		private function resize_image($image, $origW, $origH, $w, $h){
			$dst = imagecreatetruecolor($w, $h);
			//png läbipaistvus
			imagesavealpha($dst, true);
			$transColor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
			imagefill($dst, 0, 0, $transColor);
			imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
			return $dst;
		}
		
		public function addWatermark(){
			$stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
			$stampWidth = imagesx($stamp);
			$stampHeight = imagesy($stamp);
			$stampPosX = imagesx($this->myImage) - $stampWidth - $this->marginRight;
			$stampPosY = imagesy($this->myImage) - $stampHeight - $this->marginBottom;
			imageCopy($this->myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
		}
		
		public function addTextWatermark($text){
			$textColor = imagecolorallocatealpha($this->myImage, 150, 150, 150, 50);
			//RGBA alpha 0-127
			imagettftext($this->myImage, 20, 0, 10, 25, $textColor, "../../graphics/ARIAL.TTF", $text);
		}
		
		public function savePhoto($directory, $fileName){
			$target_file = $directory .$fileName;
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			if($this->imageFileType == "png"){
				if(imagejpeg($this->myImage, $target_file, 6)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
			if($this->imageFileType == "gif"){
				if(imagejpeg($this->myImage, $target_file)){
					$notice = "true";
				} else {
					$notice = "false";
				}
			}
		}
		
		public function clearImages(){
			imagedestroy($this->myImage);
			imagedestroy($this->myTempImage);
		}
		
	}//class lõppeb
?>