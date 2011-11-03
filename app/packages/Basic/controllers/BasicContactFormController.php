<?php

class BasicContactFormController extends AbstractPagesController {
	
	public $order = 4;				// order of the controller (0-10 asc)

	public function getLinksAdminMenuLeft() {
		return AbstractAdminController::getLinksAdminMenuLeftListing($this);
	}

	/**
	 * Request handler
	 * Contact form executer.
	 */
	public function subactionContactForm($args) {
		Benchmark::log("Begin of creating ContactFormController::subactionContactForm");
		
		// handel new contact form
		$contactForm = new BasicContactFormModel();
    	$contactForm->setMetaData('url', 'isEditable', ModelMetaItem::NEVER);
		$form = new Form();
		$form->addPredefinedValue('active', 0);
		$form->addPredefinedValue('author', 0);
		$form->fill($contactForm);
		$form->setLabel(tg('Contact us'));
		$form->setIdentifier('contactForm');
		$form->useCaptchaTimer(true);
		$form->useSendMail(array(
			'subject' => 'Contact form question', 
			'reply' => 'email', 
			'from' => '',
			));
		
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
		
		Benchmark::log("End of creating ContactFormController::subactionContactForm");
	}


	/**
	 * Request handler
	 * Contact form executer.
	 */
	public function actionContactForm($args) {
		Benchmark::log("Begin of creating ContactFormController::actionContactForm");
		
		// handel new contact form
		$contactForm = new BasicContactFormModel();
    	$contactForm->setMetaData('url', 'isEditable', ModelMetaItem::NEVER);
		$form = new Form();
		$form->addPredefinedValue('active', 0);
		$form->addPredefinedValue('author', 0);
		$form->fill($contactForm);
		//$form->setLabel(tg('Contact us'));
		$form->setIdentifier('contactForm');
		$form->useCaptchaTimer(true);
		$form->useSendMail(array(
			'subject' => 'Contact form question', 
			'reply' => 'email', 
			'from' => '',
			));
		
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
		
		$this->assign('title', tg('Contact form'));
		
		Benchmark::log("End of creating ContactFormController::actionContactForm");
	}

	public function getItemsLinks() {
		return $this->getItemsLinksDefault(array('actionContactForm' => tg('Contact form')), array());
	}
	
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionContactForm' => tg('Contact form')), array());
	}
	
}

?>