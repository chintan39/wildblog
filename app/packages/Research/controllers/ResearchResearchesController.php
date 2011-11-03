<?php

class ResearchResearchesController extends AbstractPagesController {
	
	public $order = 2;				// order of the controller (0-10 asc)
	var $detailMethodName = 'actionDetail';

	public function actionDetail($args) {
		// research detail processing
		$research = $args;
		
		$researchForm = new ResearchResearchFormModel(false, $research);

		$form = new Form();
		$form->setIdentifier('researchForm');
		$form->useRecaptcha(true);
		$form->setDisplayFormOnAccomplished(false);
		$form->fill($researchForm);
		
		// handeling the form request
		$form->handleRequest();
		$this->assign($form->getIdentifier(), $form->toArray());
		
		if ($research->seo_description) {
			$this->assign('seoDescription', $research->seo_description);
		}
		if ($research->seo_keywords) {
			$this->assign('seoKeywords', $research->seo_keywords);
		}
		$this->assign('title', $research->title);
		$this->assign('pageTitle', $research->title);
		$this->assign('research', $research);
	}
	

	/**
	 *
	 */
	public function actionViewResults($args) {
		$item = $args;

		$items = new ItemCollection($this->getMainListIdentifier(), $this, 'ResearchResearchResultsModel', 'getFillingsWithAnswers');
		$items->addModelParams('research', $item);
		$items->assignModelParams();
		$items->setQualification(null); // we overload filters - no qualifications are used
		//$items->setDefaultFilters();
		//$items->handleFilters();
		//$items->forceLanguage(Language::get(Themes::FRONT_END));
		$items->loadCollection();
		$items->addButtons(array());
		
		$this->assign($items->getIdentifier(), $items);
		$this->assign('csvLink', Request::getLinkItem($this->package, $this->name, 'actionViewResultsCSV', $item));
		$this->assign('title', tg('View results of ' . strtolower($this->name)));
		
		// Top menu
		$this->addTopMenu();
	}
	
	public function actionViewResultsCSV($args) {
		$fileName = $args->url . '_export_' . date('Y-m-d') . '.csv';
		Request::setMimeType('application/csv');
		header('Content-Disposition: attachment; filename=' . $fileName);
		header('Pragma: no-cache');
		header('Expires: 0');
		
		return $this->actionViewResults($args);
	}

	protected function getListingButtons() {
		
		$buttons = array(
			ItemCollection::BUTTON_EDIT => "actionEdit", 
			ItemCollection::BUTTON_REMOVE => "actionRemove", 
			ItemCollection::BUTTON_VIEW => "actionViewResults",
			ItemCollection::BUTTON_EXPORT => "actionViewResultsCSV",
			);
		return $buttons;
	}
	

	/**
	 * Returns all articles, that should be in Sitemap.
	 */
	public function getLinksSitemap() {
		return $this->getItemsLinksDefault(array(), array('actionDetail' => tg('Research')));
	}
	
}

?>