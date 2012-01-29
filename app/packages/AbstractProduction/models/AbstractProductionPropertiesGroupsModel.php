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


class AbstractProductionPropertiesGroupsModel extends AbstractCodebookModel {

	var $package = 'AbstractProduction';
	var $icon = 'codebook';
	var $table = 'properties_groups';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
		
		$this->addMetaData(AtributesFactory::create('groupDefinitionConnection')
			->setLabel('Property')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany($this->package . 'PropertiesDefinitionModel', $this->package . 'PropertiesGroupsDefinitionModel', 'group', 'property', 'groupDefinitionConnection'); // define a many:many relation to Tag through BlogTag
    }
}

?>
