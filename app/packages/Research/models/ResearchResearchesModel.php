<?php

class ResearchResearchesModel extends AbstractPagesModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'researches';
	var $languageSupportAllowed = true;

	var $questions = null;
	var $fillings = null;
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
    }
    
    public function loadResults() {
    	if (!$this->resultsLoaded) {
    		$this->loadQuestions();
    		$this->loadFillings(true);
    	}
	}

    
    public function loadQuestions() {
    	if ($this->questions !== null) {
    		return $this->questions;
    	}
		$this->questions = $this->Find('ResearchQuestionsModel');
	}
	
	public function getFillingsCount($filters=array(), $values=array()) {
		return $this->findCount('ResearchFillingsModel', $filters, $values);
	}
    
    public function loadFillings($loadAnswers=false, $filters=array(), $values=array(), $sorting=array(), $limit=array()) {
    	if ($this->fillings !== null) {
    		return $this->fillings;
    	}
		$this->addNonDbProperty('fillings');
		$this->fillings = $this->Find('ResearchFillingsModel', $filters, $values);
		if ($loadAnswers) {
    		$this->loadAnswers();
		}
	}
    
    private function loadAnswers() {
		if ($this->fillings) {
			foreach ($this->fillings as $f) {
				$f->addNonDbProperty('answers');
				$tmp = $f->Find('ResearchAnswersModel');
				$answers = array();
				if ($tmp) {
					foreach ($tmp as $answer) {
						$answers[$answer->question] = $answer;
					}
				}
				$f->answers = $answers;
			}
		}
	}
	
	
	public function getQuestions() {
		if ($this->questions === null) {
			$this->loadQuestions();
		}
		return $this->questions;
	}
	
	
	public function getFillings($filters=array(), $values=array(), $sorting=array(), $limit=array()) {
		if ($this->fillings === null) {
			$this->loadFillings(true, $filters, $values, $sorting, $limit);
		}
		return $this->fillings;
	}
	
} 

?>