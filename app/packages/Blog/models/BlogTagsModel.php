<?php

class BlogTagsModel extends AbstractCodebookModel {
	
	var $package = 'Blog';
	var $icon = 'tag', $table = 'tags';

    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BlogPostsModel', 'BlogPostsTagsModel', 'tag', 'post'); // define a many:many relation to Tag through BlogTag
    }
    
    /**
     * The method loads posts related to the tag specified by $tag parameter.
     * @param string $itemCollectionIdentifier
     * @param object $tag Tag model
     * @return object ItemsCollection
     */
    public function tagPosts($itemCollectionIdentifier, &$tag) {
    	$posts = $tag->Find('BlogPostsModel', array(), array(), array(), array('id'));
    	if (count($posts)) {
    		$values = array();
    		foreach ($posts as $p) {
    			$values[] = $p->id;
    		}
    		$filters = array(' id in (?' . str_repeat(', ?', count($values)-1) . ')');
    	} else {
			$filters = array(' 0 ');
			$values = array();
    	}

    	$post = new BlogPostsModel();
    	$post->tmpLimit = $this->tmpLimit;

    	return $post->getCollectionItems(
    		$itemCollectionIdentifier, 
    		'BlogPostsModel',	// model
    		$filters, // filters
    		$values // values
    	);
    }
    
    

} 

?>