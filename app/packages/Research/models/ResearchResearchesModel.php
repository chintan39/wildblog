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