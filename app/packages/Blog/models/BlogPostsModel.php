<?php

class BlogPostsModel extends AbstractPagesModel {
	
	var $package = 'Blog';
	var $icon = 'blog_post', $table = 'posts';

    protected function attributesDefinition() {
    	
    	parent::attributesDefinition();
    	
    	$this->addMetaData(AbstractAttributesModel::stdPublished());
    	
		$this->addMetaData(ModelMetaItem::create('postTagsConnection')
			->setLabel('Tags')
			->setType(Form::FORM_MULTISELECT_FOREIGNKEY)
			->setOptionsMethod('listSelect'));
		
    }
    
    protected function relationsDefinition() {
    	
    	parent::relationsDefinition();
    	
        $this->addCustomRelationMany('BlogTagsModel', 'BlogPostsTagsModel', 'post', 'tag', 'postTagsConnection'); // define a many:many relation to Tag through BlogTag
    }
    

	protected function sortingDefinition() {
		$this->sorting = array(new ItemSorting('published', SORTING_DESC));
	}
    
	/**
	 * Method gets some posts, that have the same tags as post specified by postsIds, 
	 * as much as it is possible.
	 * @param string $itemCollectionIdentifier
	 * @param array $postIds array of int (id of the post)
	 */
	public function getRelatedPosts($itemCollectionIdentifier, $postIds) {
		$list = array();
		$postIds = (int)$postIds[0];
		$limit = 5;

		$postClass = new BlogPostsModel();
		$postTagClass = new BlogPostsTagsModel();
		$extendedTextsJoin = QueryBuilder::getExtendedTextsJoin($postClass);
		$languageSupportWhere = QueryBuilder::getLanguageWhere($postClass);
		if ($languageSupportWhere) {
			$languageSupportWhere .= ' AND ';
		}
		$fieldnames = implode(',', $this->getFieldsSQLArray());
		$postsTagsTable = '`' . $postTagClass->getTableName() . '`';
		$postsTable = '`' . $postClass->getTableName() . '`';
		$query = "
			SELECT $fieldnames
			FROM $postsTagsTable
			LEFT JOIN $postsTable ON $postsTagsTable.post = $postsTable.id
			$extendedTextsJoin
			WHERE $postsTable.active = 1 AND $languageSupportWhere $postsTagsTable.post != $postIds AND $postsTagsTable.tag IN (
				SELECT tag
				FROM $postsTagsTable as pt
				WHERE pt.post = $postIds
			)
			GROUP BY $postsTagsTable.post
			ORDER BY count(*) DESC, $postsTable.published DESC
			LIMIT $limit
			";
		if (Config::Get('DEBUG_MODE')) {
			Benchmark::log('getRelatedPosts SQL: ' . $query); // QUERY logger
		}
		$list['items'] = AbstractDBObjectModel::importArray($this->name, dbConnection::getInstance()->fetchAll($query));
		$list['columns'] = $this->getVisibleColumnsInCollection($itemCollectionIdentifier);
		$list['itemsCount'] = count($list['items']);
		return $list;
	}
	
} 

?>
