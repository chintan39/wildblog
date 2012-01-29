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


class LinkBuildingPartnersModel extends AbstractNodesModel {
	
	var $package = 'LinkBuilding';
	var $icon = 'link_building', $table = 'partners';
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AtributesFactory::stdLink());
    	$this->addMetaData(AtributesFactory::stdDescription());

		$this->addMetaData(ModelMetaItem::create('all_pages')
			->setLabel('All pages')
			->setDescription('Visible on all pages')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
    	
		$this->addMetaData(ModelMetaItem::create('partnersTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setLinkNewItem(array('package' => $this->package, 'controller' => 'Tags', 'action' => 'actionSimpleNew', 'actionResult' => 'actionJSONListing')));

    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('LinkBuildingTagsModel', 'LinkBuildingPartnersTagsModel', 'partner', 'tag', 'partnersTagsConnection', 'partnersTagsConnection'); // define a many:many relation to Tag through BlogTag
    }
} 

?>