<?php

class BasicContactFormModel extends AbstractPagesModel {

	var $package = 'Basic';
	var $icon = 'contact_form', $table = 'contact_form';
	var $extendedTextsSupport = false;		// ability to translate columns
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdFirstname());
    	$this->addMetaData(AbstractAttributesModel::stdSurname());
    	$this->addMetaData(AbstractAttributesModel::stdEmail()->addRestrictions(Restriction::R_NOT_EMPTY));
    	
    	$this->removeMetaData('description');
    	$this->removeMetaData('seo_description');
    	$this->removeMetaData('seo_keywords');

    	$this->getMetaData('text')
    		->setType(Form::FORM_TEXTAREA)
    		->addRestrictions(Restriction::R_NOT_EMPTY)
    		->setDescription('message you would like to deliver to us');
    	$this->getMetaData('email')->setIsVisible(ModelMetaItem::NEVER);
    	$this->getMetaData('active')->setIsVisible(ModelMetaItem::NEVER);
    }
    

} 

?>