<?php

class LinkBuildingContactsModel extends AbstractSimpleModel {
	
	var $package = 'LinkBuilding';
	var $icon = 'user', $table = 'contacts';
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdAccountEmail());
    	$this->addMetaData(AbstractAttributesModel::stdFirstname());
    	$this->addMetaData(AbstractAttributesModel::stdSurname());
    	
    }

} 

?>