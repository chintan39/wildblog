<?php
/*
    Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


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