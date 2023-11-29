<?php
session_start();
/*
* File: CaptchaSecurityImages.php
* Author: IGF
* Copyright: 2012
*/
class CaptchaSecurityImages {
	private $font = 'monofont.ttf';
	public function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '0123456789abcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}
	public function CaptchaSecurityImages($width='120',$height='40',$characters='6') {
		$code = $this->generateCode($characters);
		$_SESSION['SERCURITY_CODE'] = $code;
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		//$img_bg = imagecreatefromjpeg('bg.jpg');
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, realpath($this->font), $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, realpath($this->font) , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		//header('Content-Type: image/jpeg');
		header('content-type: image/png');
		imagejpeg($image);
		imagedestroy($image);
	}
}
$width = isset($_GET['width']) ? (int)$_GET['width'] : '120';
$height = isset($_GET['height']) ? (int)$_GET['height'] : '40';
$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? (int)$_GET['characters'] :'6'; 
$captcha = new CaptchaSecurityImages($width,$height,$characters);
?>