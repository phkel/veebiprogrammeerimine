<?php
  class Photoupload {
		// public $publicTest;
    // private $privateTest;
    private $tempName;
    private $imageFileType;
    private $textToImage;
    public $exifToImage;
    private $myTempImage;
    private $myImage;
    private $marginRight;
    private $marginBottom;

    function __construct($fileToUpload, $imageFileType) {
      // $this->publicTest = "Täitsa avalik";
      // $this->privateTest = $x;
      $this->tempName = $fileToUpload;
      $this->imageFileType = $imageFileType;
      $this->marginRight = 10;
      $this->marginBottom = 10;
    }

    public function readExif() {
      //loeme EXIF infot, millal pilt tehti
			@$exif = exif_read_data($this->tempName, "ANY_TAG", 0, true);
			// var_dump($exif);
			if(!empty($exif["DateTimeOriginal"])) {
				$this->exifToImage = "Pilt tehti: " . $exif["DateTimeOriginal"];
			} else {
				$this->exifToImage = "Pildistamise aeg teadmata.";
			}
    }

    private function createImage() {
      //lähtudes failitüübist loon sobiva pildiobjekti
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg") {
				$this->myTempImage = imagecreatefromjpeg($this->tempName);
			}
			if($this->imageFileType == "png") {
				$this->myTempImage = imagecreatefrompng($this->tempName);
			}
			if($this->imageFileType == "gif") {
				$this->myTempImage = imagecreatefromgif($this->tempName);
      }
    }

    public function resizeImage($width, $height) {
      $this->createImage();
      $imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			$sizeRatio = 1;
			if($imageWidth > $imageHeight) {
				$sizeRatio = $imageWidth / $width;
			} else {
				$sizeRatio = $imageHeight / $height;
			}
			$this->myImage = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, round($imageWidth / $sizeRatio), round($imageHeight / $sizeRatio));

			// $myImageThumb = resize_image($this->myTempImage, $imageWidth, $imageHeight, $thumbWidth, $thumbHeight);
    }

    private function resize_image($image, $origW, $origH, $w, $h) {
      $dst = imagecreatetruecolor($w, $h);
      imagecopyresampled($dst, $image, 0, 0, 0, 0, $w, $h, $origW, $origH);
      return $dst;
    }

    public function addWatermark() {
      //vesimärgi lisamine
			$stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
			$stampWidth = imagesx($stamp);
			$stampHeight = imagesy($stamp);
			$stampPosX = imagesx($this->myImage) - $stampWidth - $this->marginRight;
			$stampPosY = imagesy($this->myImage) - $stampHeight - $this->marginBottom;
			imageCopy($this->myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
    }

    public function addTextWatermark($text) {
      ///lisame ka teksti vesimärgina
			$textColor = imagecolorallocatealpha($this->myImage, 200, 200, 200, 10);
      imagettftext ($this->myImage, 20, 0, 10, 25, $textColor, "../../graphics/HelveticaNeue.dfont", $text);
    }

    public function savePhoto($directory, $filename) {
      //salvestame pildi
      $target_file = $directory .$filename;
      $notice = "";
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg") {
				if(imagejpeg($this->myImage, $target_file, 90)){
					$notice = "true";
				} else {
					$notice .= "false";
				}
			}

			if($this->imageFileType == "png") {
				if(imagejpeg($this->myImage, $target_file, 6)){
					$notice = "true";
				} else {
					$notice .= "false";
				}
			}

			if($this->imageFileType == "gif") {
				if(imagejpeg($this->myImage, $target_file)){
					$notice = "true";
				} else {
					$notice .= "false";
				}
      }

      // //thumbnaili salvestamine
		  // if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg") {
			//  	if(imagejpeg($myImageThumb, $target_file_thumb, 95)){
			//  		$notice = "Ftrue";
			//  	} else {
			// 	  $notice .= "false";
      //   }
      // }      
    }

    public function clearImages() {
      imagedestroy($this->myImage);
			imagedestroy($this->myTempImage);
    }

  } //class lõppetb
?>