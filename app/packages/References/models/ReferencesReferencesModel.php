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
    	
		$options = array();
		for ($i=1; $i<=5; $i++)
			$options[] = array('id' => $i, 'value' => $i);

    	$this->addMetaData(ModelMetaItem::create('rating')
			->setLabel('Rating')
			->setDescription('how much do you like it')
			->setType(Form::FORM_SELECT)
			->setSqlType('int(11) NOT NULL DEFAULT \'1\'')
			->setOptions($options)
			->setOptionsMustBeSelected(true));
    }


} 

?>