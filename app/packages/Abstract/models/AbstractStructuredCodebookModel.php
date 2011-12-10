<?php


class AbstractStructuredCodebookModel extends AbstractCodebookModel {

	var $package="Abstract";

    protected function attributesDefinition() {

		parent::attributesDefinition();
		
		$this->addMetaData(AbstractAttributesModel::stdParent());
	}

    protected function relationsDefinition() {

    	parent::relationsDefinition();

        $this->addCustomRelation($this->name, 'parent', 'id'); // define a 1:many relation to Reaction 
    }

}

?>
