<?php

class BasicTagsModel extends AbstractCodebookModel {
	
	var $package = 'Basic';
	var $icon = 'tag', $table = 'tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('articlesTagsConnection')
			->setLabel('Articles')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
		$this->addMetaData(ModelMetaItem::create('newsTagsConnection')
			->setLabel('News')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BasicArticlesModel', 'BasicArticlesTagsModel', 'tag', 'article', 'articlesTagsConnection'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelationMany('BasicNewsModel', 'BasicNewsTagsModel', 'tag', 'news', 'newsTagsConnection'); // define a many:many relation to Tag through BlogTag
    }
    
} 

?>