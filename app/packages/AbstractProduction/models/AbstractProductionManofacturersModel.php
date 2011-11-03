<?php

class AbstractProductionManofacturersModel extends AbstractNodesModel {

	var $package = 'AbstractProduction';
	var $icon =	'manofacturer', $table = 'manofacturers';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(AbstractAttributesModel::stdLink());
		$this->addMetaData(AbstractAttributesModel::stdImage());

		$this->addMetaData(AbstractAttributesModel::stdText());
		$this->getMetadata('text')->setWysiwygType(Javascript::WYSIWYG_LITE);
		
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	}


} 

?>