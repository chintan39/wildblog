<?php

class NewsletterContactsController extends AbstractNodesController {
	
	public function actionRegister() {
		
		// handel new reference form
		$contact = new NewsletterContactsRegisterModel();

		// TODO: unregistering/handle inserting duplicate entry
		$form = new Form();
		$form->useRecaptcha(true);
		$form->setIdentifier('newsletterRegister');
		$form->addPredefinedValue('active', '1');
		$form->fill($contact);
		$form->setLabel('');
		$form->setDescription(tg('To add/remove your E-mail address to/from newsletter, just fill in the e-mail. Your name is not compulsory.') . ' ' . tg('If you want to unsubscribe, you have to fill in the token, which is written in the end of your last e-mail.') . ' ' . tg('If you want to get it, <a href="#$getTokenLink#">click here</a>.', array('getTokenLink' => Request::getLinkSimple($this->package, 'Contacts', 'actionGetToken'))));
		$form->setDisplayFormOnAccomplished(false);
		$form->addFieldAttribute('unsubscribe', 'onchange', 'if (this.form.getInputs(\'radio\',\'unsubscribe\').find(function(radio) { return radio.checked; }).value == 1) {$(\'formline_token\').show();} else {$(\'formline_token\').hide();}');
		
		if (!isset(Request::$get['_pred_']['unsubscribe']) || !Request::$get['_pred_']['unsubscribe']) {
			$form->addFieldAttribute('token', 'lineStyle', 'display: none;');
		}
		// handeling the form request
		$form->handleRequest();

		$this->assign($form->getIdentifier(), $form->toArray());

		$this->assign('title', tg('Newsletter register'));

		//$this->display('referenceAdd');
	}
	
	
	public function actionGetToken() {
		$item = new NewsletterGetTokenModel();
		$form = new Form();
		$form->fill($item);
		$form->setDescription(tg('If you want to know your token, fill in your e-mail and you will receive an email with the token.'));
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
	}
	
	
}

?>