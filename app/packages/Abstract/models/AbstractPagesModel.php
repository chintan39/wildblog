<?php

class AbstractPagesModel extends AbstractNodesModel {

	var $package='Abstract';

    protected function attributesDefinition() {

		parent::attributesDefinition();
		
		$this->addMetaData(AbstractAttributesModel::stdAuthor());
		$this->addMetaData(AbstractAttributesModel::stdText());
		$this->addMetaData(AbstractAttributesModel::stdDescription());
		$this->addMetaData(AbstractAttributesModel::stdSEODescription());
		$this->addMetaData(AbstractAttributesModel::stdSEOKeywords());
		$this->addMetaData(AbstractAttributesModel::stdRank());
	}

    protected function relationsDefinition() {

    	parent::relationsDefinition();

        $this->addCustomRelation('BaseUsersModel', 'author', 'id'); // define a 1:many relation to Reaction 
    }
	
    /**
     * Adds rank to sorting property.
     */
	protected function sortingDefinition() {
		parent::sortingDefinition();
		$this->sorting[] = new ItemSorting('rank');
	}
}

?>
