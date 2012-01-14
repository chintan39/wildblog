<?php

class LinkBuildingPartnersModel extends AbstractNodesModel {
	
	var $package = 'LinkBuilding';
	var $icon = 'link_building', $table = 'partners';
	
    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdLink());
    	$this->addMetaData(AbstractAttributesModel::stdDescription());

		$this->addMetaData(ModelMetaItem::create('all_pages')
			->setLabel('All pages')
			->setDescription('Visible on all pages')
			->setRestrictions(Restriction::R_BOOL)
			->setType(Form::FORM_CHECKBOX)
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));
    	
		$this->addMetaData(ModelMetaItem::create('partnersTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect')
			->setLinkNewItem(array('package' => $this->package, 'controller' => 'Tags', 'action' => 'actionSimpleNew', 'actionResult' => 'actionJSONListing')));

    }

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('LinkBuildingTagsModel', 'LinkBuildingPartnersTagsModel', 'partner', 'tag', 'partnersTagsConnection', 'partnersTagsConnection'); // define a many:many relation to Tag through BlogTag
    }
} 

?>