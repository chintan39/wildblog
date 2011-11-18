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
    		$this->addQualification(' id in (?' . str_repeat(', ?', count($values)-1) . ')', $values);
    	}
    	
    	$article = new BasicArticlesModel();
    	$article->setLimit($this->getLimit());

    	return $article->getCollectionItems();
    }
    
    

} 

?>