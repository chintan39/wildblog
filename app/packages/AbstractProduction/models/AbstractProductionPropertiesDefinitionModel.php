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


class AbstractProductionPropertiesDefinitionModel extends AbstractPropertiesDefinitionModel {

	var $package = 'AbstractProduction';
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$kindOptions = array(
			array('id' => AbstractPropertiesModel::KIND_DESCRIPTION, 'value' => 'Describes product'),
			array('id' => AbstractPropertiesModel::KIND_SPECIFICATION, 'value' => 'Specifies product'),
			);
		
		$this->addMetaData(ModelMetaItem::create('prop_kind')
			->setLabel('Kind')
			->setType(Form::FORM_SELECT)
			->setOptions($kindOptions)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\''));
		
		$this->addMetaData(ModelMetaItem::create('groupDefinitionConnection')
			->setLabel('Groups')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany($this->package . 'PropertiesGroupsModel', $this->package . 'PropertiesGroupsDefinitionModel', 'property', 'group', 'groupDefinitionConnection'); // define a many:many relation to Tag through BlogTag
    }
  

}

?>
