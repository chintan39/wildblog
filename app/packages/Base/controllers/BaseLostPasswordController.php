<?php

class BaseLostPasswordController extends AbstractDefaultController {
	
	public $order = 6;				// order of the controller (0-10)
	
	/**
	 * Lost Password
	 */
	public function actionLostPassword($args) {
		$item = new BaseLostPasswordFormModel();
		$form = new Form();
		$form->fill($item);
		$form->setDescription(tg('If you lost your password, fill in your login and you will receive an email with link, which will allow you to change your password.'));
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
	}
	
	
	/**
	 * Change the Lost Password
	 */
	public function actionLostPasswordChange($args) {
		$item = new BaseLostPasswordChangeFormModel();
		$item->setToken($args->token);
		$form = new Form();
		$form->fill($item);
		$form->setDescription(tg('Here you can change your pasword.'));
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
	}
	
	public function actionLostPasswordChangeDone($args) {
		$this->assign('title', tg('Your password has been changed'));
		$text = t('Now you can') 
		. ' <a href="' 
		. Request::getLinkSimple($this->package, 'Users', 'actionLogin') 
		. '" title="' . tg('login') . '">' . tg('login') . '</a> ' 
		. tg('with your new password.');
		$this->assign('text', $text);
	}
}

?>