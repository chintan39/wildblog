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


class ResearchQuestionsModel extends AbstractCodebookModel {
	
	var $package = 'Research';
	var $icon = 'references', $table = 'questions';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AtributesFactory::create('research')
			->setLabel('Research')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));
		
		$typeOptions = array(
			array('id' => Form::FORM_INPUT_TEXT, 'value' => 'Text'),
			array('id' => Form::FORM_INPUT_NUMBER, 'value' => 'Number'),
			array('id' => Form::FORM_SELECT_FOREIGNKEY, 'value' => 'Select from options'),
			array('id' => Form::FORM_MULTISELECT_FOREIGNKEY, 'value' => 'Multiselect from options'),
			array('id' => Form::FORM_MULTISELECT_FOREIGNKEY_INTERACTIVE, 'value' => 'Multeselect from options interactive'),
			array('id' => Form::FORM_RADIO_FOREIGNKEY, 'value' => 'Radio'),
			);
		$this->addMetaData(AtributesFactory::create('type')
			->setLabel('Type')
			->setType(Form::FORM_SELECT)
			->setOptions($typeOptions)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index')
			->setOptionsMustBeSelected(true));

		$this->addMetaData(AtributesFactory::create('required')
			->setLabel('Required')
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('tinyint(2) NOT NULL DEFAULT \'0\''));

		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation('ResearchResearchesModel', 'research', 'id'); // define a 1:many relation to Research
    }
    
    
} 

?>