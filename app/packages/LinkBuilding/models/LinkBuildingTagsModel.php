<?php

class LinkBuildingTagsModel extends AbstractCodebookModel {
	
	var $package = 'LinkBuilding';
	var $icon = 'tag', $table = 'tags';

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('LinkBuildingPartnersModel', 'LinkBuildingPartnersTagsModel', 'tag', 'partner');
    }

} 

?>