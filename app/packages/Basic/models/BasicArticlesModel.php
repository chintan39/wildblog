<?php

class BasicArticlesModel extends AbstractStructuredPagesModel {
	
	var $package = 'Basic';
	var $icon = 'page', $table = 'articles';
	var $languageSupportAllowed = true;

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('articlesTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BasicTagsModel', 'BasicArticlesTagsModel', 'article', 'tag', 'articlesTagsConnection', 'articlesTagsConnection'); // define a many:many relation to Tag through BlogTag
    }

    
	protected function sortingDefinition() {
		$this->sorting = array(new ItemSorting('rank'));
	}
    
} 

?>