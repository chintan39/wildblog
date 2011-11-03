<?php
/**
 * 
 */
 
class BaseLostPasswordModel extends AbstractSimpleModel {
	
	var $package = 'Base';
	var $icon = 'user', $table = 'lost_password';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdToken());
    	
		$this->addMetaData(ModelMetaItem::create('user')
			->setLabel('User')
			->setType(Form::FORM_SELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\''));
	}
	
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
	    $this->addCustomRelation($this->package . 'UsersModel', 'user', 'id'); // define a 1:many relation to manofacturer 
    
    }

    
}


?>
