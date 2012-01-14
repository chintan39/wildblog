<?php

class LinkBuildingPartnersTagsModel extends AbstractDefaultModel {
	
	var $package = 'LinkBuilding';
	var $icon = '', $table = 'partners_tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('partner')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('tag')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('LinkBuildingPartnersModel', 'partner', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('LinkBuildingTagsModel', 'tag', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>