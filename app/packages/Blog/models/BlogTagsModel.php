<?php

class BlogTagsModel extends AbstractCodebookModel {
	
	var $package = 'Blog';
	var $icon = 'tag', $table = 'tags';

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BlogPostsModel', 'BlogPostsTagsModel', 'tag', 'post'); // define a many:many relation to Tag through BlogTag
    }
    
} 

?>