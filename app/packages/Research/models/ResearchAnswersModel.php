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


class ResearchAnswersModel extends AbstractDefaultModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'answers';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

		$this->addMetaData(ModelMetaItem::create('value')
			->setLabel('Value')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('varchar(255) NOT NULL')
			->setSqlIndex('fulltext')
			);
    	
		$this->addMetaData(ModelMetaItem::create('filling')
			->setLabel('Filling')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));
		
		$this->addMetaData(ModelMetaItem::create('question')
			->setLabel('Question')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('ResearchFillingsModel', 'filling', 'id'); // define a 1:many relation to Research
	    $this->addCustomRelation('ResearchQuestionsModel', 'question', 'id'); // define a 1:many relation to Research
    }
} 

?>