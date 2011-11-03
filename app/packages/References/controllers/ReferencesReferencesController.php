<?php

class ReferencesReferencesController extends AbstractPagesController {
	
	/**
	 * Request handler
	 * Categories structure generation. 
	 */
	public function subactionReferencesList($args) {
		Benchmark::log("Begin of creating ReferencesController::subactionReferencesList");
		$references = new ItemCollection("references", $this);
		$references->setLimit(2);
		$references->loadCollection();
		$this->assign($references->getIdentifier(), $references);
		Benchmark::log("End of creating ReferencesController::subactionReferencesList");
	}

	/**
	 * Request handler
	 * Categories structure generation. 
	 */
	public function actionReferencesList($args) {
		$references = new ItemCollection("references", $this);
		$references->loadCollection();

		// assign to template
		$this->assign("title", tg("References"));
		$this->assign($references->getIdentifier(), $references);
		
		// show template
		//$this->display('references');
	}

	public function actionReferenceAdd() {	
		// handel new reference form
		$reference = new ReferencesReferencesModel();
		$form = new Form();
		$form->useRecaptcha(true);
		$form->setIdentifier("referenceNewForm");
		$form->addPredefinedValue("title", "ref");
		$form->addPredefinedValue("url", "ref");
		$form->addPredefinedValue("seo_description", "");
		$form->addPredefinedValue("seo_keywords", "");
		$form->addPredefinedValue("active", 0);
		$form->addPredefinedValue("author", 0);
		$form->fill($reference);
		$form->setLabel("");
		$form->setDescription(tg("Note: Reference will be visible after manual check by admin."));
		$defaultEmails = preg_split('/[,;]/', Config::Get('DEFAULT_EMAIL'));
		$defaultEmail = $defaultEmails[0];
		$form->useSendMail(array(
			'to' => Config::Get('DEFAULT_EMAIL'),
			'subject' => tg('New reference added'),
			));
		
		// handeling the form request
		$form->handleRequest();

		$this->assign($form->getIdentifier(), $form->toArray());

		$this->assign("title", tg("Add a new reference"));

		//$this->display('referenceAdd');
	}
	
	
	/**
	 * Returns all pages' urls, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionReferencesList' => tg('References list')), array());
	}	
}

?>