<?php

class AbstractProductionVatModel extends AbstractCodebookModel {

	var $package = 'AbstractProduction';
	var $icon = 'codebook';
	var $table = 'vat';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdRatio());
    	
    }
    
}

?>
