<?php

class LinkBuildingPartnersModel extends AbstractNodesModel {
	
	var $package = 'LinkBuilding';
	var $icon = 'link_building', $table = 'partners';
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdLink());
    	$this->addMetaData(AbstractAttributesModel::stdDescription());

		$this->addMetaData(ModelMetaItem::create('all_pages')
			->setLabel('All pages')
			->setDescription('Visible on all pages')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
    	
    }

} 

?>