<?php

class ResearchResearchResultsModel extends AbstractVirtualModel {

	var $questionsMap = null;
	var $columns = null;
	var $research = null;
	var $metaDataLoaded = false;
	var $tmpQualification = array();
	var $tmpSorting = array();
	var $tmpLimit = array();

	private function loadQuestionMapColumns() {
		// load questions and initialize questions map 
		// to get question's identificator from its ID
		$questionsMap = array();
		$questionOrder = 1;
		$columns = array();
		$columns[] = 'filled';
		foreach ($this->getResearch()->getQuestions() as $q) {
			$qName = 'question' . $questionOrder++;
			$questionsMap[$q->id] = $qName;
			$columns[] = $qName;
		}
		$this->questionsMap = $questionsMap;
		$this->columns = $columns;
	}
	
	private function getQuestionsMap() {
		if ($this->questionsMap === null) {
			$this->loadQuestionMapColumns();
		}
		return $this->questionsMap;
	}
	
	private function getColumns() {
		if ($this->columns === null) {
			$this->loadQuestionMapColumns();
		}
		return $this->columns;
	}
	
	public function getResearch() {
		if ($this->research === null) {
			$this->research = $this->loadParams['research'];
		}
		return $this->research;
	}
	
	public function getItems() {
		// load data (fillings)
		$questionsMap = $this->getQuestionsMap();
		$items = array();	
		$metaData = $this->getMetaData();
		foreach ($this->getResearch()->getFillings(array(), array(), array(), array()) as $f) {
			$newItem = new self();
			$newItem->copyMetaData($metaData);
			$newItem->filled = $f->inserted;
			foreach ($f->answers as $a) {
				$metaName = $questionsMap[$a->question];
				if (in_array($metaData[$metaName]->getType(), array(Form::FORM_MULTISELECT_FOREIGNKEY, Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE))) {
					$meta = $newItem->getMetaData($metaName);
					$options = ResearchOptionsModel::makeOptionsFromItems(ResearchOptionsModel::Search('ResearchOptionsModel', array('question = ?'), array($a->question)));
					$meta->setOptions($options);
				}
				$questionIdentificator = $questionsMap[$a->question];
				$newItem->$questionIdentificator = $a->value;
			}
			$items[] = $newItem;
		}
		return $items;
	}
	
	public function getFillingsWithAnswers($itemCollectionIdentifier, $modelName=false, $filters=array(), $values=array(), $extra=array(), $justThese=array(), $order=array(), $limit=DEFAULT_PAGING_LIMIT) {
		// create structure to display as table
		$list = array();
		$list['items'] = $this->getItems();
		$list['columns'] = $this->getColumns();
		$list['itemsCount'] = $this->getResearch()->getFillingsCount($filters, $values);
		return $list;
	}
	
	public function getVisibleColumnsInCollection() {
		return $this->getColumns();
	}
	
	private function loadMetaData() {
		$this->addMetaData(ModelMetaItem::create('filled')
			->setLabel('Filled')
			->setType(Form::FORM_INPUT_DATETIME));
		$questions = $this->getResearch()->getQuestions();
		$questionsMap = $this->getQuestionsMap();
		foreach ($questions as $q) {
			$this->addMetaData(ModelMetaItem::create($questionsMap[$q->id])
				->setLabel($q->title)
				->setType($q->type));
		}
		return $this->getMetaData();
	}
	
	public function getMetaData($name=false) {
		if (!$this->metaDataLoaded) {
			$this->metaDataLoaded = true;
			$this->loadMetaData();
		}
		return parent::getMetaData($name);
	}
	
	public function copyMetaData($metaData) {
		self::$metaData = $metaData;
		$this->metaDataLoaded = true;
	}

}

?>
