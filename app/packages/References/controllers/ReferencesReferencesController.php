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