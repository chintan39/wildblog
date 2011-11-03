<?php

  /******************************************************************

   Projectname:   CAPTCHA class
   Version:       2.0
   Author:        Pascal Rehfeldt <Pascal@Pascal-Rehfeldt.com>
   Last modified: 15. January 2006

   * GNU General Public License (Version 2, June 1991)
   *
   * This program is free software; you can redistribute
   * it and/or modify it under the terms of the GNU
   * General Public License as published by the Free
   * Software Foundation; either version 2 of the License,
   * or (at your option) any later version.
   *
   * This program is distributed in the hope that it will
   * be useful, but WITHOUT ANY WARRANTY; without even the
   * implied warranty of MERCHANTABILITY or FITNESS FOR A
   * PARTICULAR PURPOSE. See the GNU General Public License
   * for more details.

   Description:
   This class can generate CAPTCHAs, see README for more details!

  ******************************************************************/

  error_reporting(E_ALL);

  require(DIR_LIBS . 'captcha/class/Filter.class.php');  
  require(DIR_LIBS . 'captcha/class/Error.class.php');

  class Captcha
  {

  	const CAPTCHA_TYPE_STRING = 1;
  	const CAPTCHA_TYPE_MATH = 2;
  	  
    var $Length;
    var $CaptchaString;
    var $CaptchaResult;
    var $fontpath;
    var $fonts;
    var $image;
    var $type;

    function __construct($length = 5, $fontsDir = './fonts/', $type=self::CAPTCHA_TYPE_MATH)
    {

      header('Content-type: image/png');
      
      $this->Length   = $length;
      $this->type = $type;
      
      //$this->fontpath = dirname($_SERVER['SCRIPT_FILENAME']) . '/fonts/';
      $this->fontpath = $fontsDir;      
      $this->fonts    = $this->getFonts();
      $errormgr       = new Error;

      if ($this->fonts == FALSE)
      {

      	//$errormgr = new error;
      	$errormgr->addError('No fonts available!');
      	$errormgr->displayError();
      	die();
      	
      }

      if (function_exists('imagettftext') == FALSE)
      {

        $errormgr->addError('Function imagettftext does not exist.');
        $errormgr->displayError();
        die();

      }

      if ($this->type == self::CAPTCHA_TYPE_MATH) {
      	  $this->mathGen();
      } else {
      	  $this->stringGen();
      }

      $this->makeCaptcha();

    } //captcha
    
    function getFonts ()
    {
    
      $fonts = array();
    
      if ($handle = @opendir($this->fontpath))
      {
   
        while (($file = readdir($handle)) !== FALSE)
        {
       
          $extension = strtolower(substr($file, strlen($file) - 3, 3));
       
          if ($extension == 'ttf')
          {
          	
            $fonts[] = $file;
            
          }
        
        }
        
        closedir($handle);

      }
      else
      {
      	
      	return FALSE;
      	
      }
      
      if (count($fonts) == 0)
      {
      	
      	return FALSE;
      	
      }
      else
      {
      	
      	return $fonts;
      	
      }
    
    } //getFonts
    
    function getFont ($index=false)
    {
    	if ($index === false) {
    		$index = mt_rand(0, count($this->fonts) - 1);
    	}
    	return $this->fontpath . $this->fonts[$index];
    
    } //getFont

    function stringGen ()
    {

      $uppercase  = array_merge(range('A', 'H'), range('J', 'N'), range('P', 'Z'));
      
      //$lowercase  = range('a', 'z');
      $numeric    = range(2, 9); // 0 or 1 can be mistaken  with O and l

      $CharPool   = array_merge($uppercase, $numeric);
      $PoolLength = count($CharPool) - 1;

      for ($i = 0; $i < $this->Length; $i++)
      {

        $this->CaptchaString .= $CharPool[mt_rand(0, $PoolLength)];

      }
      $this->CaptchaResult = $this->CaptchaString;

    } //StringGen

    function mathGen ()
    {

      
      switch (mt_rand(1, 4)) {
      case 1: // plus
		  $a = mt_rand(1, 49);
		  $b = mt_rand(1, 49);
      	  $this->CaptchaString .= $a . '+' . $b;// . '=';
		  $this->CaptchaResult = $a + $b;
      	  break;
      case 2: // minus
		  $b = mt_rand(1, 49);
		  $this->CaptchaResult = mt_rand(1, 49);
		  $a = $b + $this->CaptchaResult;
      	  $this->CaptchaString .= $a . '-' . $b;// . '=';
      	  break;
      case 3: // times
		  $a = mt_rand(1, 9);
		  $b = mt_rand(1, 9);
      	  $this->CaptchaString .= $a . 'x' . $b;// . '=';
		  $this->CaptchaResult = $a * $b;
      	  break;
      case 4: // divide
		  $b = mt_rand(1, 9);
		  $this->CaptchaResult = mt_rand(1, 9);
		  $a = $b * $this->CaptchaResult;
      	  $this->CaptchaString .= $a . '/' . $b;// . '=';
      	  break;
      }
      
    } //StringGen

    function makeCaptcha ()
    {
      
      $imagelength = CAPTCHA_WIDTH;
      $imageheight = CAPTCHA_HEIGHT;
      $distance = (CAPTCHA_WIDTH > 40) ? ((CAPTCHA_WIDTH - 16) / $this->Length) : 20;

      $this->image = imagecreatetruecolor($imagelength, $imageheight);

      //$bgcolor     = imagecolorallocate($image, 222, 222, 222);
      $bgcolor     = imagecolorallocate($this->image, 255, 255, 255);
      imagefill($this->image, 0, 0, $bgcolor);

      $filter      = new CaptchaFilters();

      //$filter->signs($this->image, $this->getFont());

      for ($i = 0; $i < strlen($this->CaptchaString); $i++)
      {
      
      	//list($r, $g, $b) = $this->getRandomColor();
		//$stringcolor = imagecolorallocate($this->image, $r, $g, $b);
		$stringcolor = imagecolorallocate($this->image, 10, 10, 10);
		$rotate = ($this->type == self::CAPTCHA_TYPE_STRING) ? 10 : 0;
		$font = ($this->type == self::CAPTCHA_TYPE_STRING) ? $this->getFont() : $this->getFont(1);
		$leftIndent = (((5-strlen($this->CaptchaString)) * $distance) / 2) + 5;
        imagettftext($this->image, 					// resource
        			30, 							//size
        			mt_rand(-$rotate, $rotate),		// angle
        			$i * $distance + $leftIndent,	//x
                    mt_rand(40, 45),				//y
                    $stringcolor,					// color
                    $font,							// font
                    $this->CaptchaString{$i});		//letter
      
      }

      if ($this->type == self::CAPTCHA_TYPE_STRING) {
		  //$filter->noise($this->image, 10);
		  //$filter->blur($this->image, 6);
		  
		  //$filter->noiseBoxes($this->image, 6);
		  //$filter->noiseLines($this->image, 6);
		  
		  //$filter->blurGauss($this->image);	// from PHP versiosn 5.1
	
		  if (mt_rand(0, 1)) {
			  $filter->noiseLine($this->image, 2);
		  } else {
			  $filter->inverseBox($this->image, 1);
		  }
	  }

    } //MakeCaptcha

    public function display() {
      imagepng($this->image);
      imagedestroy($this->image);
      exit();
    } 
    
    
    
    function getCaptchaString ()
    {

      return $this->CaptchaString;

    } //GetCaptchaString
    
    
    function getCaptchaResult ()
    {

      return $this->CaptchaResult;

    } //GetCaptchaString
    
    
    static public function checkCaptcha($captchaString) {
    	return (strtoupper($captchaString) == $_SESSION['CAPTCHAResult']);
    }
    
    private function getRandomColor() {
    	switch (rand(1, 6)) {
			case 1: return array(60, 60, 20); break;
			case 2: return array(60, 20, 30); break;
			case 3: return array(30, 60, 30); break;
			case 4: return array(20, 20, 100); break;
			case 5: return array(20, 60, 60); break;
			case 6: return array(60, 30, 60); break;
			default: return array(30, 30, 30); break;
		}
    }
    
  } //class: captcha

?>
