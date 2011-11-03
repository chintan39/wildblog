<?php

class BasicNewsTagsModel extends AbstractDefaultModel {
	
	var $package = 'Basic';
	var $icon = '', $table = 'news_tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('news')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('tag')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('BasicNewsModel', 'news', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('BasicTagsModel', 'tag', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>