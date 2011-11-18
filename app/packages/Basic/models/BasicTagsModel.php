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
    

    /**
     * The method loads posts related to the tag specified by $tag parameter.
     * @param string $itemCollectionIdentifier
     * @param object $article Article model
     * @return object ItemsCollection
     */
    public function tagArticles($itemCollectionIdentifier, &$tag) {
    	$articles = $tag->Find('BasicArticlesModel', array(), array(), array(), array('id'));
    	if (count($articles)) {
    		$values = array();
    		foreach ($articles as $p) {
    			$values[] = $p->id;
    		}
    		$this->addQualification(' id in (?' . str_repeat(', ?', count($values)-1) . ')', $values);
    	}
    	
    	$article = new BasicArticlesModel();
    	$article->setLimit($this->getLimit());

    	return $article->getCollectionItems();
    }
    
    
    /**
     * The method loads posts related to the tag specified by $tag parameter.
     * @param string $itemCollectionIdentifier
     * @param object $article Article model
     * @return object ItemsCollection
     */
    public function tagNews($itemCollectionIdentifier, &$tag) {
    	$articles = $tag->Find('BasicNewsModel', array(), array(), array(), array('id'));
    	if (count($articles)) {
    		$values = array();
    		foreach ($articles as $p) {
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