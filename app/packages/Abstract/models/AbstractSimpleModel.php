<?php

require_once('AbstractDefaultModel.php');

class AbstractSimpleModel extends AbstractDefaultModel {

	var $package='Abstract';

    protected function attributesDefinition() {

		parent::attributesDefinition();
		
		$this->addMetaData(AbstractAttributesModel::stdInserted());
		$this->addMetaData(AbstractAttributesModel::stdUpdated());
		$this->addMetaData(AbstractAttributesModel::stdActive());
    }

    /**
     * Adds qualification to active property.
     * Must be 1 standardly.
     */
	protected function qualificationDefinition() {
		parent::qualificationDefinition();
		$this->qualification['active'] = array('active = ?' => 1);
	}
	
}

?>
