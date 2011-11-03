<?php
/**
 * 
 */
 
require_once(DIR_PACKAGES . 'Abstract' . DIRECTORY_SEPARATOR . DIR_MODELS . 'AbstractSimpleModel.php');

class BaseUsersModel extends AbstractSimpleModel {
	
	var $package='Base';
	var $icon='user', $table='users';
	var $extendedTextsSupport = false;		// ability to translate columns

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();

    	$this->addMetaData(AbstractAttributesModel::stdAccountEmail());
    	$this->addMetaData(AbstractAttributesModel::stdFirstname());
    	$this->addMetaData(AbstractAttributesModel::stdSurname());
    	$this->addMetaData(AbstractAttributesModel::stdAccountpassword());
    	$this->addMetaData(AbstractAttributesModel::stdAccountPermissions());
    	$this->addMetaData(AbstractAttributesModel::stdLastLogged());

		$this->addMetaData(ModelMetaItem::create('private_config')
			->setLabel('Private config')
			->setType(Form::FORM_TEXTAREA)
			->setSqlType('TEXT NOT NULL')
			->setFormTab(Form::TAB_PROPERTIES)
			->setExtendedTable(false)
			->setIsVisible(array('main' => false)));
	
    }

    
    /**
     * Method creates the title used in the select box.
     * @return string title of the item to use in the select box
     */
    public function makeSelectTitle() {
    	$o = trim($this->firstname . " " . $this->surname);
    	return $o ? $o : parent::makeSelectTitle();
    }
}


?>
