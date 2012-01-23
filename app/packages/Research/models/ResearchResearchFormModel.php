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


class ResearchResearchFormModel extends AbstractVirtualModel {
	
	var $research = null;
	var $questionNumber = 1;
	
    function __construct($id = false, $research = null) {
    	parent::__construct($id);
    	if ($research !== null) {
    		$this->setResearch($research);
    		$this->loadMetaData();
    	}
    }
    
	private function setResearch($research) {
		$this->research = $research;
	}
	
	private function loadMetaData() {
		$this->research->addNonDbProperty('questions');
		$this->research->questions = $this->research->Find('ResearchQuestionsModel');
		$this->id = $this->research->id;
		if ($this->research->questions) {
			foreach ($this->research->questions as $q) {
				$meta = new ModelMetaItem('question' . $this->questionNumber++);
				$meta->setLabel($q->title);
				$meta->setType($q->type);
				$meta->setDescription($q->text);
				$meta->question = $q;
				if ($meta->getType() == Form::FORM_SELECT_FOREIGNKEY || $meta->getType() == Form::FORM_RADIO_FOREIGNKEY
					|| $meta->getType() == Form::FORM_MULTISELECT_FOREIGNKEY) {
				$options = ResearchOptionsModel::makeOptionsFromItemsSelect($q->Find('ResearchOptionsModel'));
					$meta->setOptions($options);
					$meta->setOptionsMustBeSelected(true);
				}
				switch ($meta->getType()) {
					case Form::FORM_RADIO_FOREIGNKEY:
						$meta->setType(Form::FORM_RADIO);
						break;
					case Form::FORM_MULTISELECT_FOREIGNKEY:
					case Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE:
						$meta->setType(Form::FORM_MULTISELECT);
						break;
					case Form::FORM_SELECT_FOREIGNKEY:
						$meta->setType(Form::FORM_SELECT);
						break;
					case Form::FORM_INPUT_TEXT:
						break;
					case Form::FORM_INPUT_NUMBER:
						$meta->addRestrictions(Restriction::R_NUMBER);
						break;
				}
				$itemName = $meta->getName();
				$this->addMetaData($meta);
			}
		}
	}

	public function Save($forceSaving=false) {
		$newFilling = new ResearchFillingsModel();
		$newFilling->Connect($this->research);
		$newFilling->Save();
		foreach ($this->getMetadata() as $meta) {
			$newAnswer = new ResearchAnswersModel();
			$itemName = $meta->getName();
			$newAnswer->value = $this->$itemName;
			$newAnswer->Connect($newFilling);
			$newAnswer->Connect($meta->question);
			$newAnswer->Save();
		}
	}

}

?>
