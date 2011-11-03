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
   Testsuit for the CAPTCHA Class

  ******************************************************************/

  error_reporting(E_ALL);
  session_start();
  
  //Load the Class
  require('./class/Captcha.class.php');


echo '<?xml version="1.0"?>';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

      <SCRIPT language="JavaScript" type="text/javascript">
      <!--
      var Refr = function (image, elemId, rest) {
			  tmp = new Date();
			  tmp = "?"+tmp.getTime()+'&';
			  document.getElementById(elemId).src = image+tmp+rest;
	  }
      // -->
      </SCRIPT> 
  <head>

    <title>Test for the CAPTCHA Class</title>

  </head>

  <body>

    <?php

      if (!isset($_POST['submit']))
      {

        //Formular

        ?>

          <form action="" method="post">

            <img src="./captcha.php?.png" alt="CAPTCHA" id="__captcha_img" />

            <br />
            <input type="text" name="__captcha__string" size="30" /> (cAse inSeNSItivE!)
            <input type="button" value="change" name="__captcha__change" onclick="Refr('./captcha.php', '__captcha_img', '.png')" />

            <br />

            <input type="submit" name="submit" value="Submit" />

          </form>

        <?php

      }
      else
      {

        //Check if userinput and CAPTCHA String are equal

        if (Captcha::checkCaptcha($_POST['__captcha__string']))
        {

          echo 'Strings are equal.';

        }
        else
        {

          echo 'Strings are not equal.';

        }

      }

    ?>

  </body>

</html>