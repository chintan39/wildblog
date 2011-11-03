<?php

class ReferencesReferencesModel extends AbstractPagesModel {

	var $package = 'References';
	var $icon = 'references', $table = 'references';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdFirstname()->setRestrictions(Restriction::R_NOT_EMPTY));
    	$this->addMetaData(AbstractAttributesModel::stdSurname()->setRestrictions(Restriction::R_NOT_EMPTY));
    	$this->addMetaData(AbstractAttributesModel::stdCity());
    	$this->addMetaData(AbstractAttributesModel::stdEmail()->setRestrictions(Restriction::R_NOT_EMPTY));
    	
    	$this->removeMetaData('description');

    	$this->getMetaData('text')->setType(Form::FORM_TEXTAREA);
    }


} 

?>