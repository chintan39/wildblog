<?php

/**
 * Handles captcha images creating.
 */
class BaseCaptchaController extends AbstractBasicController {
	
	
	public function actionCaptcha($args) {

		require_once(DIR_LIBS . 'captcha/class/Captcha.class.php');
		
		//Create a CAPTCHA
		$captcha = new captcha(CAPTCHA_LENGTH, DIR_CAPTCHA_FONTS);
		
		//Store the String in a session
		$_SESSION['CAPTCHAResult'] = $captcha->getCaptchaResult();
		
		$captcha->display();
	}
	

}

?>