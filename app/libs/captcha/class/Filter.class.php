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
   Filters

  ******************************************************************/

  class CaptchaFilters
  {
  	
    private function getRandomColor() {
    	switch (rand(1, 6)) {
			case 1: return array(110, 130, 110); break;
			case 2: return array(130, 110, 130); break;
			case 3: return array(255, 255, 255); break;
			case 4: return array(255, 255, 255); break;
			case 5: return array(255, 255, 255); break;
			case 6: return array(255, 255, 255); break;
			default: return array(255, 255, 255); break;
		}    
    }
    
    function noise (&$image, $runs = 30)
    {
    	
	  $w = imagesx($image);
	  $h = imagesy($image);
  
      for ($n = 0; $n < $runs; $n++)
      {

        for ($i = 1; $i <= $h; $i++)
        {

          $randcolor = imagecolorallocate($image,
                                          mt_rand(0, 255),
                                          mt_rand(0, 255),
                                          mt_rand(0, 255));

          imagesetpixel($image,
                        mt_rand(1, $w),
                        mt_rand(1, $h),
                        $randcolor);

        }

      }  
  
    } //noise
    
    function noiseBoxes (&$image, $runs=6)
    {
    	
	  $w = imagesx($image);
	  $h = imagesy($image);
  
      for ($n = 0; $n < $runs; $n++)
      {

          list($r, $g, $b) = $this->getRandomColor();
		  $randcolor = imagecolorallocate($image, $r, $g, $b);

          $w1 = mt_rand(1, $w-1);
          $h1 = mt_rand(1, $h-1);
          $w2 = $w1+mt_rand(4, 30);
          $h2 = $h1+mt_rand(4, 30);
          imagerectangle($image, $w1, $h1, $w2, $h2, $randcolor);
          imagerectangle($image, $w1+1, $h1+1, $w2-1, $h2-1, $randcolor);
          if (mt_rand(0,1)) {
          	  imagerectangle($image, $w1+2, $h1+2, $w2-2, $h2-2, $randcolor);
          }
      }
    } //noiseBoxes
    
    function noiseLines (&$image, $runs=20)
    {
    	
	  $w = imagesx($image);
	  $h = imagesy($image);
  
      for ($n = 0; $n < $runs; $n++)
      {
          list($r, $g, $b) = self::getRandomColor();
		  $randcolor = imagecolorallocate($image, $r, $g, $b);

          $w1 = mt_rand(1, $w-1);
          $h1 = mt_rand(1, $h-1);
          $w2 = $w1+mt_rand(-30, 30);
          $h2 = $h1+mt_rand(-30, 30);
          imageline($image, $w1, $h1, $w2, $h2, $randcolor);
          imageline($image, $w1, $h1+1, $w2, $h2+1, $randcolor);
          if (mt_rand(0,1)) {
          	  imageline($image, $w1+1, $h1, $w2+1, $h2, $randcolor);
          }
      }
    } //noiseLines
    
    function noiseLine (&$image, $runs = 2)
    {
    	
	  $w = imagesx($image);
	  $h = imagesy($image);
  
      for ($n = 0; $n < $runs; $n++)
      {
		  $randcolor = imagecolorallocate($image, 10, 10, 10);

          $w1 = mt_rand(1, $w/4);
          $h1 = mt_rand($h/4, 3*$h/4);
          $w2 = $w-mt_rand(1, $w/4);
          $h2 = mt_rand($h/4, 3*$h/4);
          imagesetthickness($image, 5);
          imageline($image, $w1, $h1, $w2, $h2, $randcolor);
      }
    } //noiseLine
    
    function inverseBox (&$image, $runs = 2)
    {
    	
	  $w = imagesx($image);
	  $h = imagesy($image);
  
      for ($n = 0; $n < $runs; $n++)
      {
      	  $x1 = mt_rand(1, $w/2);
      	  $x2 = $x1+mt_rand($w/4, $w/2);
      	  $y1 = mt_rand(1, $h/2);
      	  $y2 = $y1+mt_rand($h/4, $h/2);
		  for ($y = $y1; $y < $y2; $y++) {
		  	  for ($x = $x1; $x < $x2; $x++) {
		  	  	  $c = imagecolorsforindex($image, imagecolorat($image, $x, $y));
		  	  	  imagesetpixel($image, $x, $y, imagecolorallocate($image, 255-$c['red'], 255-$c['green'], 255-$c['blue']));  
		  	  }
		  }
      }
    } //inverseCircle
    
    
    function signs (&$image, $font, $cells = 3)
    {
   	
	  $w = imagesx($image);
	  $h = imagesy($image);

   	  for ($i = 0; $i < $cells; $i++)
   	  {
   	  	   	  	
   	  	$centerX     = mt_rand(1, $w);
   	  	$centerY     = mt_rand(1, $h);
   	  	$amount      = mt_rand(1, 15);
   	  	
   	  	for ($n = 0; $n < $amount; $n++)
   	  	{

          $stringcolor = imagecolorallocate($image, mt_rand(185, 250), mt_rand(185, 250), mt_rand(185, 250));
          $signs = range('A', 'Z');
          $sign  = $signs[mt_rand(0, count($signs) - 1)];

   	  	  imagettftext($image, 25, 
   	  	               mt_rand(-15, 15), 
   	  	               $centerX + mt_rand(-50, 50),
   	  	               $centerY + mt_rand(-50, 50),
   	  	               $stringcolor, $font, $sign);
   	  	
   	  	}
   	  	
   	  }
   	
    } //signs
    
    function blur (&$image, $radius = 3)
    {

	  $radius  = round(max(0, min($radius, 50)) * 2);

	  $w       = imagesx($image);
	  $h       = imagesy($image);
	  
	  $imgBlur = imagecreate($w, $h);

	  for ($i = 0; $i < $radius; $i++)
	  {

		imagecopy     ($imgBlur, $image,   0, 0, 1, 1, $w - 1, $h - 1);
		imagecopymerge($imgBlur, $image,   1, 1, 0, 0, $w,     $h,     50.0000);
		imagecopymerge($imgBlur, $image,   0, 1, 1, 0, $w - 1, $h,     33.3333);
		imagecopymerge($imgBlur, $image,   1, 0, 0, 1, $w,     $h - 1, 25.0000);
		imagecopymerge($imgBlur, $image,   0, 0, 1, 0, $w - 1, $h,     33.3333);
		imagecopymerge($imgBlur, $image,   1, 0, 0, 0, $w,     $h,     25.0000);
		imagecopymerge($imgBlur, $image,   0, 0, 0, 1, $w,     $h - 1, 20.0000);
		imagecopymerge($imgBlur, $image,   0, 1, 0, 0, $w,     $h,     16.6667);
		imagecopymerge($imgBlur, $image,   0, 0, 0, 0, $w,     $h,     50.0000);
		imagecopy     ($image  , $imgBlur, 0, 0, 0, 0, $w,     $h);

	  }
	  
	  imagedestroy($imgBlur);
	  
    } //blur
    
    function blurGauss2 (&$image, $radius = 3)
    {

    	$gaussian = array(
    		array(1.0, 1.0, 1.0), 
    		array(1.0, 1.0, 1.0), 
    		array(1.0, 1.0, 1.0));
    	//$gaussian = array(array(1.0, 2.0, 1.0), array(2.0, 4.0, 2.0), array(1.0, 2.0, 1.0));
	  for ($i = 0; $i < $radius; $i++)
	  {
    	imageconvolution($image, $gaussian, 9, 0);
      }
    }
    
    function blurGauss(&$image) {
    	imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
    	imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
    	imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
    }
    
  } //class: filters

?>