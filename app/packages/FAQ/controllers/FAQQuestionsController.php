<?php

class FAQQuestionsController extends AbstractPagesController {
	
	/**
	 * Request handler
	 * Show questions list on the web 
	 */
	public function actionQuestionsList($args) {
		$questions = new ItemCollection("questions", $this);
		$questions->loadCollection();

		// assign to template
		$this->assign("title", tg("Questions"));
		$this->assign($questions->getIdentifier(), $questions);
		
	}

	/**
	 * Request handler
	 * Adding an FAQ question from the web 
	 */
	public function actionQuestionAdd() {	
		// handel new reference form
		$question = new FAQQuestionsModel();
		$form = new Form();
		$form->useRecaptcha(true);
		$form->setIdentifier("questionNewForm");
		$form->addPredefinedValue("title", "quest");
		$form->addPredefinedValue("url", "quest");
		$form->addPredefinedValue("active", 0);
		$form->addPredefinedValue("author", 0);
		$form->addPredefinedValue("answer", '');
		$form->fill($question);
		$form->setLabel("");
		$form->setDescription(tg("Note: Reference will be visible after manual check by admin."));
		// handeling the form request
		$form->handleRequest();

		$this->assign($form->getIdentifier(), $form->toArray());

		$this->assign("title", tg("Add a new question"));

	}
	
	/**
	 * Returns all articles, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array('actionQuestionsList' => tg('Questions list'), 'actionQuestionAdd' => 'Add a question'), array());
	}
}

?>