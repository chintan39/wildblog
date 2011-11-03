<?php

class BlogPostsTagsModel extends AbstractDefaultModel {
	
	var $package = 'Blog';
	var $icon = '', $table = 'posts_tags';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
		$this->addMetaData(ModelMetaItem::create('post')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

		$this->addMetaData(ModelMetaItem::create('tag')
			->setSqlType('int(11) NOT NULL DEFAULT \'0\'')
			->setSqlIndex('index'));

    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelation('BlogPostsModel', 'post', 'id'); // define a many:many relation to Tag through BlogTag
        $this->addCustomRelation('BlogTagsModel', 'tag', 'id'); // define a many:many relation to Tag through BlogTag
    }


} 

?>